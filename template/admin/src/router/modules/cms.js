// +---------------------------------------------------------------------
// | CRMEB [ CRMEBTrao quyền cho các nhà phát triển và giúp doanh nghiệp phát triển ]
// +---------------------------------------------------------------------
// | Copyright (c) 2016~2023 https://www.crmeb.com All rights reserved.
// +---------------------------------------------------------------------
// | Licensed CRMEBĐây không phải là phần mềm miễn phí và không thể xóa bản quyền liên quan đến CRMEB nếu không được phép.
// +---------------------------------------------------------------------
// | Author: CRMEB Team <admin@crmeb.com>
// +---------------------------------------------------------------------

import LayoutMain from '@/layout';
import setting from '@/setting';
let routePre = setting.routePre;

const pre = 'cms_';

export default {
  path: routePre + '/cms',
  name: 'cms',
  header: 'cms',
  redirect: {
    name: `${pre}article`,
  },
  component: LayoutMain,
  children: [
    {
      path: 'article/index/:id?',
      name: `${pre}article`,
      meta: {
        auth: ['cms-article-index'],
        title: 'Quản lý bài viết',
        keepAlive: true,
      },
      component: () => import('@/pages/cms/article/index'),
    },
    {
      path: 'article_category/index',
      name: `${pre}articleCategory`,
      meta: {
        auth: ['cms-article-category'],
        title: 'Phân loại bài viết',
      },
      component: () => import('@/pages/cms/articleCategory/index'),
    },
    {
      path: 'article/add_article/:id?',
      name: `${pre}addArticle`,
      meta: {
        auth: ['cms-article-creat'],
        title: 'Đã thêm bài viết',
        activeMenu: routePre + '/cms/article/index',
      },
      component: () => import('@/pages/cms/addArticle/index'),
    },
  ],
};
