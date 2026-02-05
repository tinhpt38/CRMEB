<template>
  <div>
    <el-card :bordered="false" shadow="never" class="ivu-mb-16" :body-style="{ padding: 0 }">
      <div class="padding-add">
        <el-form
          ref="levelFrom"
          :model="formValidate"
          :label-width="labelWidth"
          :label-position="labelPosition"
          @submit.native.prevent
          inline
        >
          <el-form-item :label="$t('router.setting.storeServiceAutoReply.replyType')" prop="type" label-for="type">
            <el-select
              v-model="formValidate.type"
              :placeholder="$t('router.setting.storeServiceAutoReply.pleaseSelect')"
              clearable
              @change="userSearchs"
              class="form_content_width"
            >
              <el-option value="text" :label="$t('router.setting.storeServiceAutoReply.textMsg')"></el-option>
              <el-option value="image" :label="$t('router.setting.storeServiceAutoReply.imageMsg')"></el-option>
            </el-select>
          </el-form-item>
          <el-form-item :label="$t('router.setting.storeServiceAutoReply.keyword')" prop="key" label-for="key">
            <el-input clearable v-model="formValidate.key" :placeholder="$t('router.setting.storeServiceAutoReply.placeholderKeyword')" class="form_content_width" />
          </el-form-item>
          <el-form-item>
            <el-button type="primary" v-db-click @click="userSearchs">{{ $t('router.setting.storeServiceAutoReply.query') }}</el-button>
          </el-form-item>
        </el-form>
      </div>
    </el-card>
    <el-card :bordered="false" shadow="never" class="ivu-mt">
      <el-button type="primary" v-db-click @click="add">{{ $t('router.setting.storeServiceAutoReply.addAutoReply') }}</el-button>
      <el-table
        :data="tabList"
        ref="table"
        class="mt14"
        v-loading="loading"
        highlight-current-row
        :no-userFrom-text="$t('router.setting.storeServiceAutoReply.noData')"
        :no-filtered-userFrom-text="$t('router.setting.storeServiceAutoReply.noFilterResult')"
      >
        <el-table-column :label="$t('router.setting.storeServiceAutoReply.id')" width="80">
          <template slot-scope="scope">
            <span>{{ scope.row.id }}</span>
          </template>
        </el-table-column>
        <el-table-column :label="$t('router.setting.storeServiceAutoReply.keywordCol')" min-width="130">
          <template slot-scope="scope">
            <span>{{ scope.row.key }}</span>
          </template>
        </el-table-column>
        <el-table-column :label="$t('router.setting.storeServiceAutoReply.replyTypeCol')" min-width="130">
          <template slot-scope="scope">
            <span>{{ scope.row.type == 'text' ? $t('router.setting.storeServiceAutoReply.textMsg') : $t('router.setting.storeServiceAutoReply.imageMsg') }}</span>
          </template>
        </el-table-column>
        <el-table-column :label="$t('router.setting.storeServiceAutoReply.replyContent')" min-width="130">
          <template slot-scope="scope">
            <span v-if="scope.row.type == 'text'">{{ scope.row.data.content }}</span>
            <div v-else class="tabBox_img" v-viewer>
              <img v-lazy="scope.row.data.src" />
            </div>
          </template>
        </el-table-column>
        <el-table-column :label="$t('router.setting.storeServiceAutoReply.isOn')" min-width="130">
          <template slot-scope="scope">
            <el-switch
              class="defineSwitch"
              :active-value="1"
              :inactive-value="0"
              v-model="scope.row.status"
              :value="scope.row.status"
              @change="onchangeIsShow(scope.row)"
              size="large"
              :active-text="$t('router.setting.storeServiceAutoReply.on')"
              :inactive-text="$t('router.setting.storeServiceAutoReply.off')"
            >
            </el-switch>
          </template>
        </el-table-column>
        <el-table-column :label="$t('router.setting.storeServiceAutoReply.action')" fixed="right" width="170">
          <template slot-scope="scope">
            <a v-db-click @click="edit(scope.row)">{{ $t('router.setting.storeServiceAutoReply.edit') }}</a>
            <el-divider direction="vertical"></el-divider>
            <a v-db-click @click="del(scope.row, $t('router.setting.storeServiceAutoReply.delAutoReplyTitle'), scope.$index)">{{ $t('router.setting.storeServiceAutoReply.del') }}</a>
          </template>
        </el-table-column>
      </el-table>
      <div class="acea-row row-right page">
        <pagination
          v-if="total"
          :total="total"
          :page.sync="formValidate.page"
          :limit.sync="formValidate.limit"
          @pagination="getList"
        />
      </div>
    </el-card>
  </div>
</template>

<script>
import { kefuAutoReplyListApi, kefuAutoReplyForm, keywordsetStatusApi } from '@/api/app';
import { mapState } from 'vuex';
export default {
  name: 'keyword',
  data() {
    return {
      grid: {
        xl: 7,
        lg: 7,
        md: 12,
        sm: 24,
        xs: 24,
      },
      loading: false,
      formValidate: {
        key: '',
        type: '',
        page: 1,
        limit: 20,
      },
      tabList: [],
      total: 0,
      columns1: [
        {
          title: 'ID',
          key: 'id',
          width: 80,
        },
        {
          title: '关键字',
          key: 'key',
          minWidth: 120,
        },
        {
          title: '回复类型',
          key: 'type',
          minWidth: 150,
        },
        {
          title: '是否显示',
          slot: 'status',
          minWidth: 120,
        },
        {
          title: '操作',
          slot: 'action',
          fixed: 'right',
          minWidth: 120,
        },
      ],
      modal: false,
      qrcode: '',
    };
  },
  created() {
    this.getList();
  },
  computed: {
    ...mapState('media', ['isMobile']),
    labelWidth() {
      return this.isMobile ? undefined : '80px';
    },
    labelPosition() {
      return this.isMobile ? 'top' : 'right';
    },
  },
  methods: {
    // 列表
    getList() {
      this.loading = true;
      kefuAutoReplyListApi(this.formValidate)
        .then(async (res) => {
          let data = res.data;
          this.tabList = data.list;
          this.total = res.data.count;
          this.loading = false;
        })
        .catch((res) => {
          this.loading = false;
          this.$message.error(res.msg);
        });
    },
    // 修改是否显示
    onchangeIsShow(row) {
      let data = {
        id: row.id,
        status: row.status,
      };
      keywordsetStatusApi(data)
        .then(async (res) => {
          this.$message.success(res.msg);
        })
        .catch((res) => {
          this.$message.error(res.msg);
        });
    },
    // 表格搜索
    userSearchs() {
      this.formValidate.page = 1;
      this.getList();
    },
    // 添加
    add() {
      this.$modalForm(kefuAutoReplyForm(0)).then(() => this.getList());
    },
    // 编辑
    edit(row) {
      this.$modalForm(kefuAutoReplyForm(row.id)).then(() => this.getList());
    },
    del(row, tit, num) {
      let delfromData = {
        title: tit,
        num: num,
        url: `app/kefu/auto_reply/del/${row.id}`,
        method: 'DELETE',
        ids: '',
      };
      this.$modalSure(delfromData)
        .then((res) => {
          this.$message.success(res.msg);
          this.tabList.splice(num, 1);
        })
        .catch((res) => {
          this.$message.error(res.msg);
        });
    },
  },
};
</script>

<style scoped>
.QRpic {
  width: 180px;
  height: 180px;
}

.QRpic img {
  width: 100%;
  height: 100%;
}
</style>
