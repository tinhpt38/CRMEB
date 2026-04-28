/**
 * Hệ thống có một bộ phương thức tích hợp sẵn. Trong trường hợp bình thường, bạn không nên sửa đổi hoặc xóa tệp này.
 * */

import { cloneDeep } from 'lodash';

/**
 * @description Theo lộ trình hiện tại, tìm tên của menu trên cùng
 * @param {String} currentPath đường dẫn hiện tại
 * @param {Array} menuList tất cả các con đường
 * */
function getHeaderName(to, menuList) {
  const allMenus = [];
  menuList.forEach((menu) => {
    const headerName = menu.path || '';
    const menus = transferMenu(menu, headerName);
    allMenus.push({
      path: menu.path,
      header: headerName,
    });
    menus.forEach((item) => AllMenus.push(item));
  });
  const currentMenu = allMenus.find((item) => {
    let path = to.meta && to.meta.activeMenu ? to.meta.activeMenu : to.path;
    if (item.path === path) {
      return true;
    } else {
      return path === getPath(to, item.path);
    }
  });
  return currentMenu ? currentMenu.header : null;
}

function getPath(to, path) {
  let params = [];
  let query = [];
  Object.keys(to.params).forEach((item) => {
    params.push(to.params[item]);
  });
  Object.keys(to.query).forEach((item) => {
    query.push(item + '=' + to.query[item]);
  });
  return path + (params.length ? '/' + params.join('/') : '') + (query.length ? '?' + query.join('&') : '');
}

function transferMenu(menu, headerName) {
  if (menu.children && menu.children.length) {
    return menu.children.reduce((all, item) => {
      all.push({
        path: item.path,
        header: headerName,
      });
      const foundChildren = transferMenu(item, headerName);
      return all.concat(foundChildren);
    }, []);
  } else {
    return [menu];
  }
}

export { getHeaderName };

/**
 * @description Theo lộ trình hiện tại, tìm tên của menu trên cùng
 * @param {String} currentPath đường dẫn hiện tại
 * @param {Array} menuList tất cả các con đường
 * */
function getHeaderSider(menuList) {
  return menuList.filter((item) => item.pid === 0);
}

export { getHeaderSider };
/**
 * @description Theo lộ trình hiện tại, tìm tên menu
 * @param {String} currentPath đường dẫn hiện tại
 * @param {Array} menuList tất cả các con đường
 * */
function getOneHeaderName(menuList, path) {
  return menuList.filter((item) => item.path === path);
}

export { getOneHeaderName };

/**
 * @description Tìm menu phụ tương ứng dựa trên tên menu thanh trên cùng hiện tại
 * @param {Array} menuList Tất cả các menu phụ
 * @param {String} headerName Menu thanh trên cùng hiện tại name
 * */
function getMenuSider(menuList, headerName = '') {
  if (headerName) {
    return menuList.filter((item) => item.path === headerName);
  } else {
    return menuList;
  }
}

export { getMenuSider };

/**
 * @description Theo lộ trình hiện tại, tìm tất cả các đường dẫn menu gốc của nó làm cơ sở để mở rộng tên mở thanh bên
 * @param {String} currentPath đường dẫn hiện tại
 * @param {Array} menuList tất cả các con đường
 * */
// function getSiderSubmenu (currentPath, menuList) {
//     const allMenus = [];
//     menuList.forEach(menu => {
//         const menus = transferSubMenu(menu, []);
//         allMenus.push({
//             path: menu.path,
//             openNames: []
//         });
//         menus.forEach(item => allMenus.push(item));
//     });
//     const currentMenu = allMenus.find(item => item.path === currentPath);
//     return currentMenu ? currentMenu.openNames : [];
// }

function getSiderSubmenu(to, menuList) {
  const allMenus = [];
  menuList.forEach((menu) => {
    const menus = transferSubMenu(menu, []);
    allMenus.push({
      path: menu.path,
      openNames: [],
    });
    menus.forEach((item) => allMenus.push(item));
  });
  const currentMenu = allMenus.find((item) => {
    if (item.openNames.length) {
      return item.path === to.path || to.path === getPath(to, item.path);
    }
  });
  return currentMenu ? currentMenu.openNames : [];
}

function transferSubMenu(menu, openNames) {
  if (menu.children && menu.children.length) {
    const itemOpenNames = openNames.concat([menu.path]);
    return menu.children.reduce((all, item) => {
      all.push({
        path: item.path,
        openNames: itemOpenNames,
      });
      const foundChildren = transferSubMenu(item, itemOpenNames);
      return all.concat(foundChildren);
    }, []);
  } else {
    return [menu].map((item) => {
      return {
        path: item.path,
        openNames: openNames,
      };
    });
  }
}

export { getSiderSubmenu };

/**
 * @description Nhận tất cả các menu con theo cách đệ quy
 * */
function getAllSiderMenu(menuList) {
  let allMenus = [];

  menuList.forEach((menu) => {
    if (menu.children && menu.children.length) {
      const menus = getMenuChildren(menu);
      menus.forEach((item) => allMenus.push(item));
    } else {
      allMenus.push(menu);
    }
  });

  return allMenus;
}

function getMenuChildren(menu) {
  if (menu.children && menu.children.length) {
    return menu.children.reduce((all, item) => {
      const foundChildren = getMenuChildren(item);
      return all.concat(foundChildren);
    }, []);
  } else {
    return [menu];
  }
}

export { getAllSiderMenu };

/**
 * @description Chuyển menu sang ngang
 * */
function flattenSiderMenu(menuList, newList) {
  menuList.forEach((menu) => {
    let newMenu = {};
    for (let i in menu) {
      if (i !== 'children') newMenu[i] = cloneDeep(menu[i]);
    }
    newList.push(newMenu);
    menu.children && flattenSiderMenu(menu.children, newList);
  });
  return newList;
}

export { flattenSiderMenu };

export const findFirstNonNullChildren = (arr) => {
  // Nếu mảng trống, trả vềnull
  if (!arr || arr.length === 0) {
    return null;
  }
  // tìm đối tượng đầu tiên
  const firstObj = arr[0];
  // Nếu đối tượng đầu tiên không có thuộc tính con, hãy trả về đối tượng đó
  if (!firstObj.children) {
    return firstObj;
  }

  // Nếu thuộc tính con của đối tượng đầu tiên là một mảng,
  // Tìm đệ quy thuộc tính con không null đầu tiên trong thuộc tính con
  if (Array.isArray(firstObj.children)) {
    return findFirstNonNullChildren(firstObj.children);
  }
  // Nếu không có thuộc tính con nào khác null trong mảng, hãy trả vềnull
  return null;
};

export const findFirstNonNullChildrenKeys = (obj, lastArr) => {
  let ids = lastArr;
  // Nếu đối tượng đầu tiên không có thuộc tính con, hãy trả về đối tượng đó
  if (!obj.children) {
    ids.push(obj.id);
    return ids;
  }
  // Nếu thuộc tính con của đối tượng đầu tiên là một mảng,
  // Tìm đệ quy thuộc tính con không null đầu tiên trong thuộc tính con
  if (Array.isArray(obj.children)) {
    ids.push(obj.id);
    return findFirstNonNullChildrenKeys(obj.children[0], ids);
  }
  return ids;
};

// Mảng lồng nhau nhiều cấp được xử lý thành mảng một chiều
export const formatFlatteningRoutes = (arr) => {
  if (arr.length <= 0) return false;
  for (let i = 0; i < arr.length; i++) {
    if (arr[i].children) {
      arr = arr.slice(0, i + 1).concat(arr[i].children, arr.slice(i + 1));
    }
  }
  return arr;
};

/**
 * @description Xác định xem danh sách 1 có chứa một mục trong danh sách 2 không
 * Vì quyền truy cập của người dùng là một mảng nên phương thức include không thể trực tiếp đưa ra kết luận.
 * */
function includeArray(list1, list2) {
  let status = false;
  if (list1 === true) {
    return true;
  } else {
    if (typeof list2 !== 'object') {
      return false;
    }
    list2.forEach((item) => {
      if (list1.includes(item)) status = true;
    });
    return status;
  }
}
export { includeArray };
