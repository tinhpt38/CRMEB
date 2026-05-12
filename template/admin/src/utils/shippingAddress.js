const WARD_KEYWORDS = ['Phường', 'Xã', 'Thị trấn', 'Quận', 'Huyện', 'Thị xã'];
const NEXT_ADDRESS_SEGMENT = '(?=\\s+(?:Tỉnh|Thành phố|Phường|Xã|Thị trấn|Quận|Huyện|Thị xã)\\b|$)';

function normalizeAddressText(raw) {
  return String(raw || '').replace(/\s+/g, ' ').trim();
}

function getWardOptions(cityTree, province) {
  const provinceNode = (cityTree || []).find((item) => item.label === province);
  if (!provinceNode || !provinceNode.children || !provinceNode.children.length) {
    return [];
  }
  return provinceNode.children.map((item) => item.label);
}

function stripLeadingProvinceDuplicates(text, province) {
  let rest = normalizeAddressText(text);
  const normalizedProvince = normalizeAddressText(province);
  if (!normalizedProvince) return rest;

  while (rest.startsWith(normalizedProvince)) {
    rest = rest.slice(normalizedProvince.length).trim();
  }

  return rest;
}

function extractLeadingProvince(text) {
  const thanhPhoMatch = text.match(new RegExp(`^(Thành phố\\s+.+?)${NEXT_ADDRESS_SEGMENT}`, 'iu'));
  if (thanhPhoMatch) return thanhPhoMatch[1].trim();

  const tinhMatch = text.match(new RegExp(`^(Tỉnh\\s+.+?)${NEXT_ADDRESS_SEGMENT}`, 'iu'));
  if (tinhMatch) return tinhMatch[1].trim();

  return '';
}

function extractLeadingWard(text) {
  const normalizedText = normalizeAddressText(text);
  if (!normalizedText) {
    return { ward: '', detail: '' };
  }

  for (let i = 0; i < WARD_KEYWORDS.length; i += 1) {
    const keyword = WARD_KEYWORDS[i];
    if (!normalizedText.startsWith(`${keyword} `)) continue;

    const parts = normalizedText.split(' ');
    return {
      ward: parts.slice(0, 2).join(' '),
      detail: parts.slice(2).join(' ').trim(),
    };
  }

  return { ward: '', detail: normalizedText };
}

function parseStructuredShippingAddress(text) {
  const province = extractLeadingProvince(text);
  if (!province) return null;

  const rest = stripLeadingProvinceDuplicates(text, province);
  const { ward, detail } = extractLeadingWard(rest);

  return {
    province,
    ward,
    detail,
  };
}

function parseShippingAddressByKeywords(text) {
  if (!text) {
    return { detail: '', ward: '', province: '' };
  }

  let wardIdx = -1;
  WARD_KEYWORDS.forEach((keyword) => {
    const idx = text.lastIndexOf(`${keyword} `);
    if (idx > wardIdx) wardIdx = idx;
  });

  let province = '';
  let ward = '';
  let detail = text;

  if (wardIdx >= 0) {
    const head = text.slice(0, wardIdx).trim();
    const tail = text.slice(wardIdx).trim();
    const tailParts = tail.split(' ');
    ward = tailParts.slice(0, 2).join(' ');
    const tailDetail = tailParts.slice(2).join(' ').trim();
    const headParts = head.split(' ');

    if (headParts[0] === 'Tỉnh' || headParts[0] === 'Thành') {
      province = headParts.slice(0, 3).join(' ').trim() || head;
      const headDetail = headParts.slice(3).join(' ').trim();
      detail = [headDetail, tailDetail].filter(Boolean).join(' ').trim();
    } else {
      province = headParts.slice(0, 2).join(' ').trim() || head;
      const headDetail = headParts.slice(2).join(' ').trim();
      detail = [headDetail, tailDetail].filter(Boolean).join(' ').trim();
    }
  }

  detail = stripLeadingProvinceDuplicates(detail, province);

  return {
    detail,
    ward,
    province,
  };
}

export function parseShippingAddress(raw, cityTree = []) {
  const text = normalizeAddressText(raw);
  if (!text) {
    return { detail: '', ward: '', province: '' };
  }

  const structured = parseStructuredShippingAddress(text);
  if (structured) {
    return structured;
  }

  const provinces = (cityTree || [])
    .map((item) => item.label)
    .filter(Boolean)
    .sort((a, b) => b.length - a.length);

  for (let i = 0; i < provinces.length; i += 1) {
    const province = provinces[i];
    if (!text.startsWith(province)) continue;

    let rest = stripLeadingProvinceDuplicates(text, province);
    const wardOptions = getWardOptions(cityTree, province).sort((a, b) => b.length - a.length);

    for (let j = 0; j < wardOptions.length; j += 1) {
      const ward = wardOptions[j];
      if (!rest.startsWith(ward)) continue;
      const detail = rest.slice(ward.length).trim();
      return { detail, ward, province };
    }

    const { ward, detail } = extractLeadingWard(rest);
    return { detail, ward, province };
  }

  return parseShippingAddressByKeywords(text);
}

export function composeShippingAddress({ detail, ward, province }) {
  const normalizedDetail = normalizeAddressText(detail);
  const normalizedWard = normalizeAddressText(ward);
  const normalizedProvince = normalizeAddressText(province);
  return [normalizedProvince, normalizedProvince, normalizedWard, normalizedDetail].filter(Boolean).join(' ');
}

export function buildShippingAddressLines(raw, cityTree = []) {
  const labels = ['Tỉnh/thành phố', 'Xã/phường', 'Địa chỉ chi tiết'];
  const parsed = parseShippingAddress(raw, cityTree);
  const values = [parsed.province, parsed.ward, parsed.detail];

  return labels.map((label, index) => ({
    label,
    value: values[index] || '-',
  }));
}

export function createAddressSuggestions(options, queryString) {
  const query = normalizeAddressText(queryString).toLowerCase();
  const list = (options || []).filter(Boolean);
  const filtered = query ? list.filter((item) => item.toLowerCase().includes(query)) : list;
  return filtered.map((value) => ({ value }));
}
