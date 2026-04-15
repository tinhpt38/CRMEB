import { i18n } from '@/i18n/index.js';

const t = (key) => i18n.t(key);

const TABLE_TITLE = {
  image: t('message.productAdd.image'),
  sellPrice: t('message.productAdd.sellPrice'),
  costPrice: t('message.productAdd.costPrice'),
  otPrice: t('message.productAdd.otPrice'),
  stock: t('message.productAdd.stock'),
  productCode: t('message.productAdd.productCode'),
  barcode: t('message.productAdd.barcode'),
  weight: t('message.productAdd.weight'),
  volume: t('message.productAdd.volume'),
  defaultSelectedSpec: t('message.productAdd.defaultSelectedSpec'),
  operation: t('message.productList.operation'),
  virtualProduct: t('message.productAdd.virtualProduct'),
};

// Export table head data
export const GoodsTableHead = [
  {
    title: TABLE_TITLE.image,
    slot: 'pic',
    align: 'center',
    minWidth: '80px',
  },
  {
    title: TABLE_TITLE.sellPrice,
    slot: 'price',
    align: 'center',
    minWidth: '120px',
  },
  {
    title: TABLE_TITLE.costPrice,
    slot: 'cost',
    align: 'center',
    minWidth: '120px',
  },
  {
    title: TABLE_TITLE.otPrice,
    slot: 'ot_price',
    align: 'center',
    minWidth: '120px',
  },
  {
    title: TABLE_TITLE.stock,
    slot: 'stock',
    align: 'center',
    minWidth: '120px',
  },
  {
    title: TABLE_TITLE.productCode,
    slot: 'bar_code',
    align: 'center',
    minWidth: '120px',
  },
  {
    title: TABLE_TITLE.barcode,
    slot: 'bar_code_number',
    align: 'center',
    minWidth: '120px',
  },
  {
    title: TABLE_TITLE.weight,
    slot: 'weight',
    align: 'center',
    minWidth: '95px',
  },
  {
    title: TABLE_TITLE.volume,
    slot: 'volume',
    align: 'center',
    minWidth: '95px',
  },
  {
    title: TABLE_TITLE.defaultSelectedSpec,
    slot: 'selected_spec',
    fixed: 'right',
    align: 'center',
    minWidth: '100px',
  },
  {
    title: TABLE_TITLE.operation,
    slot: 'action',
    fixed: 'right',
    align: 'center',
    minWidth: '120px',
  },
];
// Virtual product - card code/coupon
export const VirtualTableHead = [
  {
    title: TABLE_TITLE.image,
    slot: 'pic',
    align: 'center',
    minWidth: 80,
  },
  {
    title: TABLE_TITLE.sellPrice,
    slot: 'price',
    align: 'center',
    minWidth: 120,
  },
  {
    title: TABLE_TITLE.costPrice,
    slot: 'cost',
    align: 'center',
    minWidth: 120,
  },
  {
    title: TABLE_TITLE.otPrice,
    slot: 'ot_price',
    align: 'center',
    minWidth: 120,
  },
  {
    title: TABLE_TITLE.stock,
    slot: 'stock',
    align: 'center',
    minWidth: 120,
  },
  {
    title: TABLE_TITLE.productCode,
    slot: 'bar_code',
    align: 'center',
    minWidth: 120,
  },
  {
    title: TABLE_TITLE.virtualProduct,
    slot: 'fictitious',
    align: 'center',
    minWidth: 120,
  },
  {
    title: TABLE_TITLE.defaultSelectedSpec,
    slot: 'selected_spec',
    fixed: 'right',
    align: 'center',
    minWidth: 90,
  },
  {
    title: TABLE_TITLE.operation,
    slot: 'action',
    fixed: 'right',
    align: 'center',
    minWidth: 120,
  },
];
// Virtual product
export const VirtualTableHead2 = [
  {
    title: TABLE_TITLE.image,
    slot: 'pic',
    align: 'center',
    minWidth: 80,
  },
  {
    title: TABLE_TITLE.sellPrice,
    slot: 'price',
    align: 'center',
    minWidth: 120,
  },
  {
    title: TABLE_TITLE.costPrice,
    slot: 'cost',
    align: 'center',
    minWidth: 120,
  },
  {
    title: TABLE_TITLE.otPrice,
    slot: 'ot_price',
    align: 'center',
    minWidth: 120,
  },
  {
    title: TABLE_TITLE.stock,
    slot: 'stock',
    align: 'center',
    minWidth: 120,
  },
  {
    title: TABLE_TITLE.productCode,
    slot: 'bar_code',
    align: 'center',
    minWidth: 120,
  },
  {
    title: TABLE_TITLE.defaultSelectedSpec,
    slot: 'selected_spec',
    fixed: 'right',
    align: 'center',
    minWidth: 90,
  },
  {
    title: TABLE_TITLE.operation,
    slot: 'action',
    fixed: 'right',
    align: 'center',
    minWidth: 120,
  },
];

export const columns2 = [
  {
    title: TABLE_TITLE.image,
    slot: 'pic',
    align: 'center',
    minWidth: 80,
  },
  {
    title: TABLE_TITLE.sellPrice,
    slot: 'price',
    align: 'center',
    minWidth: 95,
  },
  {
    title: TABLE_TITLE.costPrice,
    slot: 'cost',
    align: 'center',
    minWidth: 95,
  },
  {
    title: TABLE_TITLE.otPrice,
    slot: 'ot_price',
    align: 'center',
    minWidth: 95,
  },
  {
    title: TABLE_TITLE.stock,
    slot: 'stock',
    align: 'center',
    minWidth: 95,
  },
  {
    title: TABLE_TITLE.productCode,
    slot: 'bar_code',
    align: 'center',
    minWidth: 120,
  },
  {
    title: TABLE_TITLE.barcode,
    slot: 'bar_code_number',
    align: 'center',
    minWidth: 120,
  },
  {
    title: TABLE_TITLE.weight,
    slot: 'weight',
    align: 'center',
    minWidth: 95,
  },
  {
    title: TABLE_TITLE.volume,
    slot: 'volume',
    align: 'center',
    minWidth: 95,
  },
  {
    title: TABLE_TITLE.operation,
    slot: 'action',
    fixed: 'right',
    align: 'center',
    minWidth: 120,
  },
];

export const columns3 = [
  {
    title: TABLE_TITLE.image,
    slot: 'pic',
    align: 'center',
    minWidth: 80,
  },
  {
    title: TABLE_TITLE.sellPrice,
    slot: 'price',
    align: 'center',
    minWidth: 95,
  },
  {
    title: TABLE_TITLE.costPrice,
    slot: 'cost',
    align: 'center',
    minWidth: 95,
  },
  {
    title: TABLE_TITLE.otPrice,
    slot: 'ot_price',
    align: 'center',
    minWidth: 95,
  },
  {
    title: TABLE_TITLE.stock,
    slot: 'stock',
    align: 'center',
    minWidth: 95,
  },
  {
    title: TABLE_TITLE.productCode,
    slot: 'bar_code',
    align: 'center',
    minWidth: 120,
  },
  {
    title: TABLE_TITLE.barcode,
    slot: 'bar_code_number',
    align: 'center',
    minWidth: 120,
  },
  {
    title: TABLE_TITLE.operation,
    slot: 'action',
    fixed: 'right',
    align: 'center',
    minWidth: 120,
  },
];

// Custom message type options
export const CustomList = [
  {
    value: 'text',
    label: t('message.productAdd.textInput'),
  },
  {
    value: 'number',
    label: t('message.productAdd.number'),
  },
  {
    value: 'email',
    label: t('message.productAdd.email'),
  },
  {
    value: 'data',
    label: t('message.productAdd.date'),
  },
  {
    value: 'time',
    label: t('message.productAdd.time'),
  },
  {
    value: 'id',
    label: t('message.productAdd.idCard'),
  },
  {
    value: 'phone',
    label: t('message.productAdd.phone'),
  },
  {
    value: 'img',
    label: TABLE_TITLE.image,
  },
];

export const RuleValidate = {
  store_name: [{ required: true, message: t('message.productAdd.productNamePlaceholder'), trigger: 'blur' }],
  cate_id: [
    {
      required: true,
      message: t('message.productAdd.productCategoryRequired'),
      trigger: 'change',
      type: 'array',
      min: '1',
    },
  ],
  unit_name: [{ required: true, message: t('message.productAdd.unitPlaceholder'), trigger: 'blur' }],
  slider_image: [
    {
      required: true,
      message: t('message.productAdd.productSliderRequired'),
      type: 'array',
      trigger: 'change',
    },
  ],
  spec_type: [{ required: true, message: t('message.productAdd.selectSpecType'), trigger: 'change' }],
  is_virtual: [{ required: true, message: t('message.productAdd.selectProductType'), trigger: 'change' }],
  selectRule: [{ required: true, message: t('message.productAdd.selectSpecAttribute'), trigger: 'change' }],
  temp_id: [
    {
      required: true,
      message: t('message.productAdd.freightTemplateRequired'),
      trigger: 'change',
      type: 'number',
    },
  ],
  presale_time: [
    {
      required: true,
      type: 'array',
      message: t('message.productAdd.selectActivityTime'),
      trigger: 'change',
    },
  ],
  logistics: [
    {
      required: true,
      type: 'array',
      min: 1,
      message: t('message.productAdd.selectLogisticsMethod'),
      trigger: 'change',
    },
    {
      type: 'array',
      max: 2,
      message: t('message.productAdd.selectLogisticsMethod'),
      trigger: 'change',
    },
  ],
  give_integral: [{ type: 'integer', message: t('message.productAdd.enterInteger') }],
};
