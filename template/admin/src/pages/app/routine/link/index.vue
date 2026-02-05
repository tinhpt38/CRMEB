<template>
  <div>
    <el-card :bordered="false" shadow="never" class="ivu-mt">
      <el-row class="mb20">
        <el-col :span="24">
          <el-button type="primary" v-db-click @click="add" class="mr10">{{ $t('message.pages.app.routineLink.createLink') }}</el-button>
        </el-col>
      </el-row>
      <el-table
        :data="tableList"
        v-loading="loading"
        highlight-current-row
        :no-userFrom-text="$t('message.pages.app.routineLink.noData')"
        :no-filtered-userFrom-text="$t('message.pages.app.routineLink.noFilterResult')"
      >
        <el-table-column :label="$t('message.pages.app.routineLink.id')" width="80">
          <template slot-scope="scope">
            <span>{{ scope.row.id }}</span>
          </template>
        </el-table-column>
        <el-table-column :label="$t('message.pages.app.routineLink.name')" width="180">
          <template slot-scope="scope">
            <span>{{ scope.row.title }}</span>
          </template>
        </el-table-column>
        <el-table-column :label="$t('message.pages.app.routineLink.jumpUrl')" width="180">
          <template slot-scope="scope">
            <span>{{ scope.row.path }}</span>
          </template>
        </el-table-column>
        <el-table-column :label="$t('message.pages.app.routineLink.systemLink')" min-width="200">
          <template slot-scope="scope">
            <span>{{ scope.row.http_url }}</span>
            <a class="ml10" v-db-click @click="onCopy(scope.row.http_url)">{{ $t('message.pages.app.routineLink.copy') }}</a>
          </template>
        </el-table-column>
        <el-table-column :label="$t('message.pages.app.routineLink.wechatLink')" min-width="200">
          <template slot-scope="scope">
            <span>{{ scope.row.url }}</span>
            <a class="ml10" v-db-click @click="onCopy(scope.row.url)">{{ $t('message.pages.app.routineLink.copy') }}</a>
          </template>
        </el-table-column>
        <el-table-column :label="$t('message.pages.app.routineLink.addTime')" min-width="130">
          <template slot-scope="scope">
            <span>{{ scope.row.add_time }}</span>
          </template>
        </el-table-column>
        <el-table-column :label="$t('message.pages.app.routineLink.expireTime')" min-width="130">
          <template slot-scope="scope">
            <span>{{ scope.row.expire_time }}</span>
          </template>
        </el-table-column>
        <el-table-column :label="$t('message.pages.app.routineLink.action')" fixed="right" width="120">
          <template slot-scope="scope">
            <a v-db-click @click="edit(scope.row)">{{ $t('message.pages.app.routineLink.edit') }}</a>
            <el-divider direction="vertical"></el-divider>
            <a v-db-click @click="del(scope.row, $t('message.pages.app.routineLink.delLinkTitle'), scope.$index)">{{ $t('message.pages.app.routineLink.del') }}</a>
          </template>
        </el-table-column>
      </el-table>
      <div class="acea-row row-right page">
        <pagination
          v-if="total"
          :total="total"
          :page.sync="tableFrom.page"
          :limit.sync="tableFrom.limit"
          @pagination="routineSchemeList"
        />
      </div>
    </el-card>
  </div>
</template>

<script>
import { mapState } from 'vuex';
import { routineSchemeList, routineSchemeForm, routineSchemeDel } from '@/api/app';
export default {
  name: 'index',
  computed: {
    ...mapState('media', ['isMobile']),
    labelWidth() {
      return this.isMobile ? undefined : '80px';
    },
    labelPosition() {
      return this.isMobile ? 'top' : 'right';
    },
  },
  data() {
    return {
      verModal: false,
      total: 20,
      tableFrom: {
        page: 1,
        limit: 15,
      },
      loading: false,
      tableList: [],
    };
  },
  created() {
    this.routineSchemeList();
  },
  methods: {
    // 添加
    add() {
      this.$modalForm(routineSchemeForm(0)).then((res) => {
        this.routineSchemeList();
      });
    },
    onCopy(copyData) {
      this.$copyText(copyData)
        .then((message) => {
          this.$message.success(this.$t('message.pages.app.routineLink.copySuccess'));
        })
        .catch((err) => {
          this.$message.error(this.$t('message.pages.app.routineLink.copyFail'));
        });
    },
    // 列表
    routineSchemeList() {
      this.loading = true;
      routineSchemeList(this.tableFrom)
        .then((res) => {
          this.tableList = res.data.list;
          this.total = res.data.count;
          this.loading = false;
        })
        .catch((err) => {
          this.$message.error(err.msg);
          this.loading = false;
        });
    },
    // 添加
    edit(row) {
      this.$modalForm(routineSchemeForm(row.id)).then((res) => {
        this.routineSchemeList();
      });
    },
    // 删除
    del(row, tit, num) {
      let delfromData = {
        title: tit,
        num: num,
        url: `app/routine/scheme_del/${row.id}`,
        method: 'DELETE',
        ids: '',
      };
      this.$modalSure(delfromData)
        .then((res) => {
          this.$message.success(res.msg);
          this.tableList.splice(num, 1);
        })
        .catch((res) => {
          this.$message.error(res.msg);
        });
    },
  },
};
</script>

<style lang="scss" scoped></style>
