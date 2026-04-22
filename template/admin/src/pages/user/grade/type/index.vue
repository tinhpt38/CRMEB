<template>
  <div>
    <el-card :bordered="false" shadow="never" class="ivu-mt">
      <!-- <el-button type="primary" v-db-click @click="addType">添加类型</el-button> -->
      <el-table
        class="mt14"
        :data="tbody"
        v-loading="loading"
        highlight-current-row
        :no-userFrom-text="$t('userGradeType.noData')"
        :no-filtered-userFrom-text="$t('userGradeType.noFilteredResult')"
      >
        <el-table-column :label="$t('userGradeType.id')" width="80">
          <template slot-scope="scope">
            <span>{{ scope.row.id }}</span>
          </template>
        </el-table-column>
        <el-table-column :label="$t('userGradeType.memberName')" min-width="130">
          <template slot-scope="scope">
            <span>{{ scope.row.title }}</span>
          </template>
        </el-table-column>
        <el-table-column :label="$t('userGradeType.validDays')" min-width="130">
          <template slot-scope="scope">
            <span>{{ scope.row.vip_day === -1 ? $t('userGradeType.forever') : scope.row.vip_day }}</span>
          </template>
        </el-table-column>
        <el-table-column :label="$t('userGradeType.originalPrice')" min-width="90">
          <template slot-scope="scope">
            <span>{{ scope.row.price }}</span>
          </template>
        </el-table-column>
        <el-table-column :label="$t('userGradeType.discountPrice')" min-width="90">
          <template slot-scope="scope">
            <span>{{ scope.row.pre_price }}</span>
          </template>
        </el-table-column>
        <el-table-column :label="$t('userGradeType.isEnabled')" min-width="100">
          <template slot-scope="scope">
            <el-switch
              :active-value="0"
              :inactive-value="1"
              v-model="scope.row.is_del"
              :value="scope.row.is_del"
              @change="onchangeIsShow(scope.row)"
              size="large"
            >
            </el-switch>
          </template>
        </el-table-column>
        <el-table-column :label="$t('userGradeType.sort')" min-width="90">
          <template slot-scope="scope">
            <span>{{ scope.row.sort }}</span>
          </template>
        </el-table-column>
        <el-table-column :label="$t('userGradeType.operation')" fixed="right" width="170">
          <template slot-scope="scope">
            <a href="javascript:" v-db-click @click="editType(scope.row)">{{ $t('userGradeType.edit') }}</a>
            <!-- <el-divider direction="vertical" v-if="scope.row.type !== 'free' && scope.row.type !== 'ever'" />
            <a
              v-if="scope.row.type !== 'free' && scope.row.type !== 'ever'"
              href="javascript:"
              v-db-click @click="del(scope.row, '删除类型', scope.$index)"
              >删除</a
            > -->
          </template>
        </el-table-column>
      </el-table>
    </el-card>
    <el-dialog
      :visible.sync="modal"
      :title="`${rowModelType}${rowEdit && rowEdit.title}${$t('userGradeType.member')}`"
      width="540px"
      @closed="cancel"
    >
      <form-create v-if="modal" v-model="fapi" :rule="rule" :option="options" @submit="onSubmit"></form-create>
    </el-dialog>
  </div>
</template>

<script>
import { userMemberShip, memberShipSave, memberCard, deleteCard } from '@/api/user';

export default {
  name: 'list',
  data() {
    return {
      tbody: [],
      loading: false,
      modal: false,
      rowEdit: {},
      rowModelType: this.$t('userGradeType.edit'),
      options: {
        form: {
          labelWidth: '100px',
        },
      },
      rule: [
        {
          type: 'hidden',
          field: 'id',
          value: '',
        },
        {
          type: 'hidden',
          field: 'type',
          value: '',
        },
        {
          type: 'input',
          field: 'title',
          title: this.$t('userGradeType.memberName'),
          value: '',
          props: {
            disabled: false,
            placeholder: this.$t('userGradeType.inputMemberName'),
          },
          validate: [
            {
              type: 'string',
              max: 10,
              min: 1,
              message: this.$t('userGradeType.inputLength1To10'),
              requred: true,
            },
          ],
        },
        {
          type: 'InputNumber',
          field: 'vip_day',
          title: this.$t('userGradeType.validDays'),
          value: null,
          props: {
            precision: 0,
            disabled: false,
            type: 'text',
            placeholder: this.$t('userGradeType.inputValidDays'),
            controls: false,
          },
          style: {
            width: '100%',
          },
          validate: [
            {
              type: 'number',
              max: 1000000,
              min: 0,
              message: this.$t('userGradeType.maxMinNumberMessage'),
              requred: true,
            },
          ],
        },
        {
          type: 'InputNumber',
          field: 'price',
          title: this.$t('userGradeType.originalPrice'),
          value: null,
          props: {
            min: 0,
            disabled: false,
            placeholder: this.$t('userGradeType.inputOriginalPrice'),
            controls: false,
          },
          style: {
            width: '100%',
          },
          validate: [
            {
              type: 'number',
              max: 1000000,
              min: 0,
              message: this.$t('userGradeType.maxMinNumberMessage'),
              requred: true,
            },
          ],
        },
        {
          type: 'InputNumber',
          field: 'pre_price',
          title: this.$t('userGradeType.discountPrice'),
          value: null,
          props: {
            min: 0,
            disabled: false,
            placeholder: this.$t('userGradeType.inputDiscountPrice'),
            controls: false,
          },
          style: {
            width: '100%',
          },
          validate: [
            {
              type: 'number',
              max: 1000000,
              min: 0,
              message: this.$t('userGradeType.maxMinNumberMessage'),
              requred: true,
            },
          ],
        },
        {
          type: 'InputNumber',
          field: 'sort',
          title: this.$t('userGradeType.sort'),
          value: 0,
          props: {
            min: 1,
            max: 1000000,
            disabled: false,
            placeholder: this.$t('userGradeType.inputSort'),
            controls: false,
          },
          style: {
            width: '100%',
          },
          validate: [
            {
              type: 'number',
              max: 1000000,
              min: 0,
              message: this.$t('userGradeType.maxMinNumberMessage'),
              requred: true,
            },
          ],
        },
      ],
      fapi: {
        id: '',
        pre_price: null,
        price: null,
        sort: null,
        title: '',
        type: 'owner',
        vip_day: null,
      },
    };
  },
  created() {
    this.getMemberShip();
  },
  mounted() {},
  methods: {
    onchangeIsShow(row) {
      let data = {
        id: row.id,
        is_del: row.is_del,
      };
      memberCard(data)
        .then((res) => {
          this.$message.success(res.msg);
          this.getMemberShip();
        })
        .catch((err) => {
          this.$message.error(err.msg);
        });
    },
    cancel() {
      this.fapi = {
        id: '',
        pre_price: null,
        price: null,
        sort: null,
        title: '',
        type: 'owner',
        vip_day: null,
      };
      this.rule.forEach((e) => {
        e.value = null;
      });
    },
    getMemberShip() {
      this.loading = true;
      userMemberShip()
        .then((res) => {
          this.loading = false;
          const { count, list } = res.data;
          this.total = count;
          this.tbody = list;
        })
        .catch((err) => {
          this.loading = false;
          this.$message.error(err.msg);
        });
    },
    addType() {
      this.rowEdit.id = 0;
      this.rowModelType = this.$t('userGradeType.add');
      this.rule[1].value = 'owner';
      this.rule[3].props.disabled = false;
      this.rule[5].props.disabled = false;
      this.rowEdit.title = '';
      this.modal = true;
    },
    del(row, tit, num) {
      let delfromData = {
        title: tit,
        num: num,
        url: `user/member_ship/delete/${row.id}`,
        method: 'DELETE',
        ids: '',
      };
      this.$modalSure(delfromData)
        .then((res) => {
          this.$message.success(res.msg);
          this.getMemberShip();
        })
        .catch((res) => {
          this.$message.error(res.msg);
        });
    },
    editType(row) {
      this.rule.forEach((item) => {
        for (const key in row) {
          if (row.hasOwnProperty(key)) {
            if (item.field === key) {
              if (key === 'vip_day') {
                if (row[key] === -1 || row[key] == this.$t('userGradeType.forever')) {
                  item.type = 'input';
                  item.props.disabled = true;
                  row[key] = this.$t('userGradeType.forever');
                  item.validate = [{ type: 'string', message: '', requred: true }];
                } else {
                  item.props.disabled = false;
                  item.props.min = 1;

                }
              }
              if (['price'].includes(key)) {
                row[key] = parseFloat(row[key]);
              }
              if (['pre_price'].includes(key)) {
                row[key] = parseFloat(row[key]);
                if (row[key]) {
                  item.props.disabled = false;
                } else {
                  item.props.disabled = true;
                }
              }
              item.value = row[key];
            }
          }
        }
      });
      this.rowModelType = this.$t('userGradeType.edit');
      this.rowEdit = JSON.parse(JSON.stringify(row));
      this.modal = true;
    },
    onSubmit(formData) {
      memberShipSave(this.rowEdit.id, formData)
        .then((res) => {
          this.modal = false;
          this.$message.success(res.msg);
          this.getMemberShip();
          this.cancel();
        })
        .catch((err) => {
          this.$message.error(err.msg);
        });
    },
  },
};
</script>
<style lang="scss" scoped></style>
