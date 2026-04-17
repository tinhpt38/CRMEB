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
        
        const translateIfExist = (str) => {
          if (typeof str === 'string' && this.$t) {
            return this.$t(str);
          }
          return str;
        };

        if (data.title) data.title = translateIfExist(data.title);

        const walkRules = (rules) => {
          rules.forEach((e) => {
            if (e.title) e.title = translateIfExist(e.title);
            if (e.info) e.info = translateIfExist(e.info);
            if (e.props) {
              if (e.props.placeholder) e.props.placeholder = translateIfExist(e.props.placeholder);
              if (e.props.activeText) e.props.activeText = translateIfExist(e.props.activeText);
              if (e.props.inactiveText) e.props.inactiveText = translateIfExist(e.props.inactiveText);
            }
            if (e.options) {
              e.options.forEach(opt => {
                 if (opt.label) opt.label = translateIfExist(opt.label);
              });
            }
            if (e.control) {
               e.control.forEach(ctrl => {
                 if (ctrl.rule) walkRules(ctrl.rule);
               });
            }
          });
        };
        
        if (data.rules) walkRules(data.rules);

        data.rules.forEach((e) => {
          e.title += '：';
        });
        this.$msgbox({
          title: data.title,
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
                      this.$message.success(res.msg || this.$t('message.setting.success') || '提交成功');
                      resolve(res);
                    })
                    .catch((err) => {
                      this.$message.error(err.msg || this.$t('message.setting.failed') || '提交失败');
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
