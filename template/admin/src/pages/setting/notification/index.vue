<template>
  <div class="message">
    <el-card :bordered="false" shadow="never" class="ivu-mt" :body-style="{ padding: '0 20px 20px' }">
      <div>
        <el-tabs v-model="currentTab" @tab-click="changeTab">
          <el-tab-pane
            :label="item.label"
            :name="item.value.toString()"
            v-for="(item, index) in headerList"
            :key="index"
          />
        </el-tabs>
      </div>
      <!-- Hidden — China-specific WeChat/mini-program sync buttons
      <el-row class="mb14" v-if="currentTab == 1">
        <el-col>
          <el-button v-auth="['app-wechat-template-sync']" type="primary" v-db-click @click="routineTemplate"
            >Đồng bộ hóa tin nhắn đăng ký applet</el-button
          >
          <el-button v-auth="['app-wechat-template-sync']" type="primary" v-db-click @click="wechatTemplate"
            >Đồng bộ hóa tin nhắn mẫu WeChat</el-button
          >
        </el-col>
      </el-row>
      -->
      <el-row class="mb14" v-if="currentTab == 3">
        <el-col>
          <el-button type="primary" v-db-click @click="notificationForm(0)">Thêm thông báo</el-button>
          <el-button type="primary" plain v-db-click @click="goChannelPage">Kênh thông báo</el-button>
        </el-col>
      </el-row>
      <!-- Hidden — China-specific WeChat mini-program alert -->
      <el-table
        :data="levelLists"
        ref="table"
        class="mt14"
        v-loading="loading"
        highlight-current-row
        no-userFrom-text="Chưa có dữ liệu"
        no-filtered-userFrom-text="Chưa có kết quả lọc nào"
      >
        <el-table-column label="ID" width="80">
          <template slot-scope="scope">
            <span>{{ scope.row.id }}</span>
          </template>
        </el-table-column>
        <el-table-column label="Loại thông báo" min-width="130">
          <template slot-scope="scope">
            <span>{{ scope.row.name }}</span>
          </template>
        </el-table-column>
        <el-table-column label="Mô tả kịch bản thông báo" min-width="130">
          <template slot-scope="scope">
            <span>{{ scope.row.title }}</span>
          </template>
        </el-table-column>
        <el-table-column label="Thông báo trang web" min-width="130">
          <template slot-scope="scope">
            <el-switch
              v-if="scope.row.is_system !== 0"
              :active-value="1"
              :inactive-value="2"
              v-model="scope.row.is_system"
              :value="scope.row.is_system"
              @change="changeSwitch($event, scope.row, 'is_system')"
              size="large"
              :disabled="scope.row.is_system == 0"
            >
            </el-switch>
            <div v-else>-</div>
          </template>
        </el-table-column>
        <!-- Hidden — China-specific WeChat OA template column -->

        <el-table-column label="Gửi tin nhắn văn bản" min-width="130">
          <template slot-scope="scope">
            <el-switch
              v-if="scope.row.is_sms !== 0"
              :active-value="1"
              :inactive-value="2"
              v-model="scope.row.is_sms"
              :value="scope.row.is_sms"
              @change="changeSwitch($event, scope.row, 'is_sms')"
              size="large"
            >
            </el-switch>
            <div v-else>-</div>
          </template>
        </el-table-column>
        <!-- Hidden — China-specific WeChat Enterprise column -->
        <el-table-column label="Telegram" min-width="130" v-if="currentTab != 1">
          <template slot-scope="scope">
            <el-switch
              v-if="scope.row.is_telegram !== 0"
              :active-value="1"
              :inactive-value="2"
              v-model="scope.row.is_telegram"
              :value="scope.row.is_telegram"
              @change="changeSwitch($event, scope.row, 'is_telegram')"
              size="large"
              :disabled="scope.row.is_telegram == 0"
            >
            </el-switch>
            <div v-else>-</div>
          </template>
        </el-table-column>
        <!-- Hidden — China-specific mini-program subscription column -->
        <el-table-column label="Thao tác" fixed="right" :width="currentTab == 3 ? 130 : 70">
          <template slot-scope="scope">
            <a class="setting btn" v-db-click @click="setting(scope.row)">Cài đặt</a>
            <template v-if="currentTab == 3">
              <el-divider direction="vertical"></el-divider>
              <a class="setting btn" v-db-click @click="notificationForm(scope.row.id)">Chỉnh sửa</a>
              <el-divider direction="vertical"></el-divider>
              <a class="setting btn" v-db-click @click="del(scope.row, 'Xóa', scope.$index)">Xóa</a>
            </template>
          </template>
        </el-table-column>
      </el-table>
    </el-card>
  </div>
</template>

<script>
import { getNotificationList, getNotificationInfo, noticeStatus, notificationForm } from '@/api/notification.js';
import { routineSyncTemplate, wechatSyncTemplate } from '@/api/app';
export default {
  data() {
    return {
      modalTitle: '',
      notificationModal: false,
      headerList: [
        { label: 'Thông báo thành viên', value: '1' },
        { label: 'Thông báo nền tảng', value: '2' },
        { label: 'Thông báo tùy chỉnh', value: '3' },
      ],
      levelLists: [],
      currentTab: '1',
      loading: false,
      formData: {},
    };
  },
  created() {
    this.changeTab(this.currentTab);
  },
  methods: {
    changeSwitch(e, row, type) {
      noticeStatus(type, row[type], row.id)
        .then((res) => {
          this.$message.success(res.msg);
        })
        .catch((err) => {
          this.$message.error(err.msg);
        });
    },
    notificationForm(id) {
      this.$modalForm(notificationForm(id)).then(() => this.changeTab());
    },
    changeTab() {
      getNotificationList(this.currentTab).then((res) => {
        this.levelLists = res.data;
      });
    },
    // Đồng bộ hóa tin nhắn đăng ký
    routineTemplate() {
      routineSyncTemplate()
        .then((res) => {
          this.$message.success(res.msg);
          this.changeTab(this.currentTab);
        })
        .catch((res) => {
          this.$message.error(res.msg);
        });
    },
    // Đồng bộ hóa tin nhắn mẫu WeChat
    wechatTemplate() {
      wechatSyncTemplate()
        .then((res) => {
          this.$message.success(res.msg);
          this.changeTab(this.currentTab);
        })
        .catch((res) => {
          this.$message.error(res.msg);
        });
    },
    // bật tắt
    changeStatus() {},
    // danh sách
    notice() {},
    // cài đặt
    setting(row) {
      this.$router.push({
        path: this.$routeProStr + '/setting/notification/notificationEdit?id=' + row.id,
      });
    },
    goChannelPage() {
      this.$router.push({
        path: this.$routeProStr + '/setting/notification/channel',
      });
    },
    getData(keys, row, item) {
      this.formData = {};
      getNotificationInfo(row.id, item).then((res) => {
        keys.map((i, v) => {
          this.formData[i] = res.data[i];
        });
        this.formData.type = item;
        this.notificationModal = true;
      });
    },
    // xóa bỏ
    del(row, tit, num) {
      let delfromData = {
        title: tit,
        num: num,
        url: `setting/notification/del_not/${row.id}`,
        method: 'DELETE',
        ids: '',
      };
      this.$modalSure(delfromData)
        .then((res) => {
          this.$message.success(res.msg);
          this.levelLists.splice(num, 1);
        })
        .catch((res) => {
          this.$message.error(res.msg);
        });
    },
  },
};
</script>

<style lang="scss" scoped>
::v-deep .el-tabs__item {
  height: 54px !important;
  line-height: 54px !important;
}
.message ::v-deep .ivu-table-header thead tr th {
  padding: 8px 16px;
}
.message ::v-deep .ivu-tabs-tab {
  border-radius: 0 !important;
}
.table-box {
  padding: 20px;
}
.is-table {
  display: flex;
  /* justify-content: space-around; */
  justify-content: center;
}
.btn {
  padding: 6px 0px;
  cursor: pointer;
  font-size: 12px;
  border-radius: 3px;
}
.is-switch-close {
  background-color: #504444;
}
.is-switch {
  background-color: #eb5252;
}
.notice-list {
  background-color: #308cf5;
  margin: 0 15px;
}
.table {
  padding: 0 18px;
}
.alert_title {
  margin-bottom: 5px;
  font-weight: 700;
}
</style>
