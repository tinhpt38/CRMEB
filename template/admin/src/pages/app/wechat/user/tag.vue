<template>
  <div>
    <el-card :bordered="false" shadow="never" class="save_from ivu-mt">
      <el-button type="primary" v-db-click @click="add">{{ $t('message.pages.app.wechatTag.add') + $route.meta.title }}</el-button>
      <el-table
        :data="tabList"
        ref="table"
        class="mt14"
        v-loading="loading"
        highlight-current-row
        :no-userFrom-text="$t('message.pages.app.wechatTag.noData')"
        :no-filtered-userFrom-text="$t('message.pages.app.wechatTag.noFilterResult')"
      >
        <el-table-column label="ID" width="80">
          <template slot-scope="scope">
            <span>{{ scope.row.id }}</span>
          </template>
        </el-table-column>
        <el-table-column :label="$t('message.pages.app.wechatTag.tagName')" min-width="130">
          <template slot-scope="scope">
            <span>{{ scope.row.name }}</span>
          </template>
        </el-table-column>
        <el-table-column :label="$t('message.pages.app.wechatTag.count')" min-width="130">
          <template slot-scope="scope">
            <span>{{ scope.row.count }}</span>
          </template>
        </el-table-column>
        <el-table-column :label="$t('message.pages.app.wechatTag.action')" fixed="right" width="170">
          <template slot-scope="scope">
            <a v-db-click @click="edit(scope.row)">{{ $t('message.pages.app.wechatTag.edit') }}</a>
            <el-divider direction="vertical"></el-divider>
            <a v-db-click @click="del(scope.row, $t('message.pages.app.wechatTag.delTagTitle'), scope.$index)">{{ $t('message.pages.app.wechatTag.del') }}</a>
          </template>
        </el-table-column>
      </el-table>
    </el-card>
  </div>
</template>

<script>
import {
  wechatTagListApi,
  wechatTagCreateApi,
  wechatTagEditApi,
  wechatGroupListApi,
  wechatGroupCreateApi,
  wechatGroupEditApi,
} from '@/api/app';
export default {
  name: 'tag',
  data() {
    return {
      FromData: null,
      loading: false,
      tabList: [],
    };
  },
  watch: {
    $route(to, from) {
      this.getList();
    },
  },
  created() {
    this.getList();
  },
  methods: {
    // 添加
    add() {
      if (this.$route.path === this.$routeProStr + '/app/wechat/wechat_user/user/tag') {
        this.$modalForm(wechatTagCreateApi()).then(() => this.getList());
      } else {
        this.$modalForm(wechatGroupCreateApi()).then(() => this.getList());
      }
    },
    // 编辑
    edit(row) {
      if (this.$route.path === this.$routeProStr + '/app/wechat/wechat_user/user/tag') {
        this.$modalForm(wechatTagEditApi(row.id)).then(() => this.getList());
      } else {
        this.$modalForm(wechatGroupEditApi(row.id)).then(() => this.getList());
      }
    },
    // 删除
    del(row, tit, num) {
      let delfromData = null;
      if (this.$route.path === this.$routeProStr + '/app/wechat/wechat_user/user/tag') {
        delfromData = {
          title: tit,
          num: num,
          url: `app/wechat/tag/${row.id}`,
          method: 'DELETE',
          ids: '',
        };
      } else {
        delfromData = {
          title: tit,
          num: num,
          url: `app/wechat/group/${row.id}`,
          method: 'DELETE',
          ids: '',
        };
      }
      this.$modalSure(delfromData)
        .then((res) => {
          this.$message.success(res.msg);
          this.tabList.splice(num, 1);
        })
        .catch((res) => {
          this.$message.error(res.msg);
        });
    },
    // 列表
    getList() {
      this.loading = true;
      let fountion;
      if (this.$route.path === this.$routeProStr + '/app/wechat/wechat_user/user/tag') {
        fountion = wechatTagListApi();
      } else {
        fountion = wechatGroupListApi();
      }
      fountion
        .then(async (res) => {
          let data = res.data;
          this.tabList = data.list.list;
          this.loading = false;
        })
        .catch((res) => {
          this.loading = false;
          this.$message.error(res.msg);
        });
    },
    pageChange(index) {
      this.formValidate.page = index;
      this.getList();
    },
  },
};
</script>

<style scoped></style>
