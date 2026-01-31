import request from '@/libs/request';
import Vue from 'vue';

let fApi;
let unique = 1;
import formCreate from '@form-create/element-ui';

const uniqueId = () => ++unique;
export default function modalForm(formRequestPromise, config = {}) {
  const h = this.$createElement;
  return new Promise((resolve, reject) => {
    formRequestPromise
      .then(({ data }) => {
        if (!data.config) data.config = {};
        data.config.submitBtn = false;
        data.config.resetBtn = false;
        if (!data.config.form) data.config.form = {};
        if (!data.config.formData) data.config.formData = {};
        data.config.formData = { ...data.config.formData, ...config.formData };
        data.config.form.labelWidth = '105px';
        data.config.global = {
          upload: {
            props: {
              onSuccess(rep, file) {
                if (rep.status === 200) {
                  file.url = rep.data.src;
                }
              },
            },
          },
          frame: {
            props: {
              onLoad(e) {
                console.log(e, 'rep');
                e.fApi = fApi;
              },
            },
          },
          inputNumber: {
            props: {
              controls: false,
            },
          },
        };
        data = Vue.observable(data);
        const action = (data.action || '').toLowerCase();
        const isCategory = action.includes('category');
        const isProtection = action.includes('protection');
        const categoryMap = { cate_name: 'categoryName', pid: 'parentCategory', pic: 'categoryIcon', sort: 'sort', is_show: 'status' };
        const protectionMap = { title: 'guaranteeName', content: 'guaranteeContent', image: 'image', status: 'status', sort: 'sort' };
        data.rules.forEach((e) => {
          let key = null;
          if (isCategory && categoryMap[e.field]) {
            key = 'message.pages.product.classify.form.' + categoryMap[e.field];
          } else if (isProtection && protectionMap[e.field]) {
            key = 'message.pages.product.protectionList.form.' + protectionMap[e.field];
          }
          if (key && this.$te(key)) {
            e.title = this.$t(key) + (e.title && e.title.endsWith('：') ? '' : '：');
          } else if (!e.title || !e.title.endsWith('：')) {
            e.title = (e.title || '') + '：';
          }
          // Dịch options trạng thái (显示/隐藏)
          if ((isCategory && e.field === 'is_show') || (isProtection && e.field === 'status')) {
            const opts = e.options || e.props?.options;
            if (Array.isArray(opts)) {
              opts.forEach((o) => {
                if (Number(o.value) === 1) o.label = this.$t('message.pages.product.classify.show');
                else if (Number(o.value) === 0) o.label = this.$t('message.pages.product.classify.hide');
              });
            }
          }
          // Dịch option "顶级分类" trong select danh mục cha
          if (isCategory && e.field === 'pid') {
            const opts = e.options || e.props?.options;
            if (Array.isArray(opts)) {
              opts.forEach((o) => {
                if (Number(o.value) === 0 && this.$te('message.pages.product.classify.form.topCategory')) o.label = this.$t('message.pages.product.classify.form.topCategory');
              });
            }
          }
        });
        let modalTitle = config.titleKey ? this.$t(config.titleKey) : (config.title || data.title);
        if (!config.titleKey) {
          const isAdd = action.includes('create') || action.includes('/0');
          if (isCategory) modalTitle = this.$t(isAdd ? 'message.pages.product.classify.form.addCategoryTitle' : 'message.pages.product.classify.form.editCategoryTitle');
          else if (isProtection) modalTitle = this.$t(isAdd ? 'message.pages.product.protectionList.form.addGuaranteeTitle' : 'message.pages.product.protectionList.form.editGuaranteeTitle');
        }
        this.$msgbox({
          title: modalTitle,
          showCancelButton: true,
          customClass: config.class || 'modal-form',
          mask: false,
          closeOnClickModal: false,
          message: h('div', { class: 'common-form-create', key: uniqueId() }, [
            h('formCreate', {
              props: {
                rule: data.rules,
                option: data.config,
              },
              on: {
                mounted: ($f) => {
                  fApi = $f;
                },
              },
            }),
          ]),
          beforeClose: (action, instance, done) => {
            const fn = () => {
              setTimeout(() => {
                instance.confirmButtonLoading = false;
              }, 500);
            };

            if (action === 'confirm') {
              instance.confirmButtonLoading = true;
              fApi.submit(
                (formData) => {
                  request[data.method.toLowerCase()](data.action, formData)
                    .then((res) => {
                      done();
                      this.$message.success(res.msg || this.$t('message.common.submitSuccess'));
                      resolve(res);
                    })
                    .catch((err) => {
                      this.$message.error(err.msg || this.$t('message.common.submitFail'));
                      // reject(err);
                    })
                    .finally(() => {
                      fn();
                    });
                },
                () => fn(),
              );
            } else {
              fn();
              done();
            }
          },
        });
      })
      .catch((e) => {
        this.$message.error(e.msg || '--');
      });
  });
}
