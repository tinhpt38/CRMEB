<template>
  <div class="notification-channel">
    <el-card :bordered="false" shadow="never" class="ivu-mt">
      <el-row class="mb14">
        <el-col>
          <el-button type="primary" v-db-click @click="openCreate">Thêm kênh</el-button>
        </el-col>
      </el-row>
      <el-table :data="list" v-loading="loading" class="mt14" highlight-current-row>
        <el-table-column label="ID" width="80">
          <template slot-scope="scope">{{ scope.row.id }}</template>
        </el-table-column>
        <el-table-column label="Tên kênh" min-width="180">
          <template slot-scope="scope">{{ scope.row.name }}</template>
        </el-table-column>
        <el-table-column label="Mã kênh" min-width="150">
          <template slot-scope="scope">{{ scope.row.channel_key }}</template>
        </el-table-column>
        <el-table-column label="Loại" width="120">
          <template slot-scope="scope">{{ scope.row.channel_type }}</template>
        </el-table-column>
        <el-table-column label="Trạng thái" width="150">
          <template slot-scope="scope">
            <el-switch
              :active-value="1"
              :inactive-value="0"
              v-model="scope.row.status"
              @change="changeStatus(scope.row)"
            />
          </template>
        </el-table-column>
        <el-table-column label="Thao tác" fixed="right" width="220">
          <template slot-scope="scope">
            <a v-db-click @click="openEdit(scope.row)">Sửa</a>
            <el-divider direction="vertical"></el-divider>
            <a v-db-click @click="testChannel(scope.row)">Gửi thử</a>
            <el-divider direction="vertical"></el-divider>
            <a v-db-click @click="remove(scope.row)">Xóa</a>
          </template>
        </el-table-column>
      </el-table>
    </el-card>

    <el-dialog :visible.sync="dialogVisible" :title="form.id ? 'Sửa kênh' : 'Thêm kênh'" width="640px" :close-on-click-modal="false">
      <el-form ref="formRef" :model="form" :rules="rules" label-width="140px">
        <el-form-item label="Loại kênh" prop="channel_type">
          <el-select v-model="form.channel_type" style="width: 100%">
            <el-option label="telegram" value="telegram"></el-option>
          </el-select>
        </el-form-item>
        <el-form-item label="Mã kênh" prop="channel_key">
          <el-input v-model.trim="form.channel_key" placeholder="VD: telegram_ops" />
        </el-form-item>
        <el-form-item label="Tên kênh" prop="name">
          <el-input v-model.trim="form.name" placeholder="VD: Telegram Ops" />
        </el-form-item>
        <el-form-item label="Bot Token" prop="bot_token">
          <el-input v-model.trim="form.bot_token" placeholder="Nhập Telegram Bot Token" />
        </el-form-item>
        <el-form-item label="Chat ID" prop="chat_id">
          <el-input v-model.trim="form.chat_id" placeholder="Nhập Chat ID / Group ID" />
        </el-form-item>
      </el-form>
      <span slot="footer">
        <el-button @click="dialogVisible = false">Hủy</el-button>
        <el-button type="primary" :loading="saving" @click="submit">Lưu</el-button>
      </span>
    </el-dialog>
  </div>
</template>

<script>
import {
  noticeChannelList,
  noticeChannelSave,
  noticeChannelUpdate,
  noticeChannelSetStatus,
  noticeChannelDelete,
  noticeChannelTestTelegram,
} from '@/api/noticeChannel';

export default {
  name: 'notification_channel',
  data() {
    return {
      loading: false,
      saving: false,
      dialogVisible: false,
      list: [],
      form: {
        id: 0,
        channel_type: 'telegram',
        channel_key: '',
        name: '',
        bot_token: '',
        chat_id: '',
      },
      rules: {
        channel_key: [{ required: true, message: 'Vui lòng nhập mã kênh', trigger: 'blur' }],
        name: [{ required: true, message: 'Vui lòng nhập tên kênh', trigger: 'blur' }],
        bot_token: [{ required: true, message: 'Vui lòng nhập bot token', trigger: 'blur' }],
        chat_id: [{ required: true, message: 'Vui lòng nhập chat id', trigger: 'blur' }],
      },
    };
  },
  created() {
    this.getList();
  },
  methods: {
    getList() {
      this.loading = true;
      noticeChannelList()
        .then((res) => {
          this.list = res.data || [];
        })
        .finally(() => {
          this.loading = false;
        });
    },
    openCreate() {
      this.form = {
        id: 0,
        channel_type: 'telegram',
        channel_key: '',
        name: '',
        bot_token: '',
        chat_id: '',
      };
      this.dialogVisible = true;
    },
    openEdit(row) {
      let config = {};
      try {
        config = typeof row.config === 'string' ? JSON.parse(row.config || '{}') : row.config || {};
      } catch (e) {
        config = {};
      }
      this.form = {
        id: row.id,
        channel_type: row.channel_type,
        channel_key: row.channel_key,
        name: row.name,
        bot_token: config.bot_token || '',
        chat_id: config.chat_id || '',
      };
      this.dialogVisible = true;
    },
    submit() {
      this.$refs.formRef.validate((valid) => {
        if (!valid) return;
        this.saving = true;
        const payload = {
          channel_type: this.form.channel_type,
          channel_key: this.form.channel_key,
          name: this.form.name,
          bot_token: this.form.bot_token,
          chat_id: this.form.chat_id,
          status: 1,
        };
        const req = this.form.id ? noticeChannelUpdate(this.form.id, payload) : noticeChannelSave(payload);
        req
          .then((res) => {
            this.$message.success(res.msg || 'Lưu thành công');
            this.dialogVisible = false;
            this.getList();
          })
          .finally(() => {
            this.saving = false;
          });
      });
    },
    changeStatus(row) {
      noticeChannelSetStatus(row.id, row.status).then((res) => this.$message.success(res.msg || 'Cập nhật thành công'));
    },
    remove(row) {
      noticeChannelDelete(row.id).then((res) => {
        this.$message.success(res.msg || 'Xóa thành công');
        this.getList();
      });
    },
    testChannel(row) {
      let config = {};
      try {
        config = typeof row.config === 'string' ? JSON.parse(row.config || '{}') : row.config || {};
      } catch (e) {
        config = {};
      }
      noticeChannelTestTelegram({
        bot_token: config.bot_token || '',
        chat_id: config.chat_id || '',
        text: 'Thong bao test tu CRMEB',
      }).then((res) => {
        this.$message.success(res.msg || 'Đã gửi thử');
      });
    },
  },
};
</script>

