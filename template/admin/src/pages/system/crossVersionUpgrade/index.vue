<template>
  <div class="cross-version-upgrade">
    <!-- 功能 Tabs -->
    <el-card :bordered="false" shadow="never" class="ivu-mt">
      <el-tabs v-model="currentTab" @tab-click="handleTabClick">
        <el-tab-pane :label="$t('message.systemUpgrade.systemUpgrade')" name="upgrade"></el-tab-pane>
        <el-tab-pane :label="$t('message.systemUpgrade.upgradeRecords')" name="logs"></el-tab-pane>
      </el-tabs>

      <!-- 系统升级 Tab -->
      <div v-if="currentTab === 'upgrade'">
        <div v-if="showUpgradeComplete" class="new-version-summary">
          <div class="summary-header">
            <span class="summary-icon-wrap">
              <img src="@/assets/images/new.png" class="summary-icon-img" alt="upgrade" />
            </span>
            <div class="summary-title">{{ $t('message.systemUpgrade.versionUpgradeCompleted') }}</div>
            <div class="summary-sub">
              {{ $t('message.systemUpgrade.uploadMiniProgramTip') }}
              <a href="https://mp.weixin.qq.com" target="_blank" style="color: #409eff">{{ $t('message.systemUpgrade.clickToPublish') }}</a>
            </div>
            <div class="summary-actions">
              <el-button type="primary" @click="handleUploadMini" :loading="uploadingMini">{{ $t('message.systemUpgrade.uploadMiniProgram') }}</el-button>
              <el-button class="ml8" @click="handleRefreshStatus">{{ $t('message.systemUpgrade.notUploadNow') }}</el-button>
            </div>
          </div>
        </div>
        <!-- 发现新版本摘要（status = 1） -->
        <div
          v-if="remoteStatus.status === 1 && downloadStage === 'idle' && !showUpgradeComplete"
          class="new-version-summary"
        >
          <div class="summary-header">
            <span class="summary-icon-wrap">
              <img src="@/assets/images/upgrade.png" class="summary-icon-img" alt="upgrade" />
            </span>
            <div class="summary-title">
              {{ $t('message.systemUpgrade.newVersionFound') }}
              <span v-if="upgradeablePackage">
                v{{ upgradeablePackage.first_version }}.{{ upgradeablePackage.second_version }}.{{
                  upgradeablePackage.third_version
                }}
              </span>
            </div>
            <div class="summary-sub">{{ $t('message.systemUpgrade.newVersionFoundDesc') }}</div>
            <div class="summary-actions">
              <el-button type="primary" @click="startDownload" :disabled="startingDownload" :loading="startingDownload"
                >{{ $t('message.systemUpgrade.upgradeNow') }}</el-button
              >
              <el-button class="ml8 check-update-btn" @click="checkRemoteUpdate" :loading="checkingRemote"
                >{{ $t('message.systemUpgrade.checkUpdates') }}</el-button
              >
            </div>
          </div>
          <div v-if="upgradeablePackage" class="summary-content">
            <div class="content-title">
              V{{ upgradeablePackage.first_version }}.{{ upgradeablePackage.second_version }}.{{
                upgradeablePackage.third_version
              }}
              {{ $t('message.systemUpgrade.updateDescription') }}
              <span class="release-time" v-if="upgradeablePackage.release_time"
                >{{ $t('message.systemUpgrade.updateDateLabel') }}{{ upgradeablePackage.release_time }}</span
              >
            </div>
            <div class="content-desc" v-html="upgradeablePackage.content"></div>
          </div>
        </div>

        <div
          v-if="(remoteStatus.status === 0 || remoteStatus.status === -1) && downloadStage === 'idle'"
          class="new-version-summary"
        >
          <div class="summary-header">
            <span class="summary-icon-wrap">
              <img src="@/assets/images/new.png" class="summary-icon-img" alt="upgrade" />
            </span>
            <div class="summary-title">{{ $t('message.systemUpgrade.currentVersion') }} {{ versionInfo.current_version_main || '-' }}</div>
            <div class="summary-sub">{{ $t('message.systemUpgrade.currentVersionCode') }} {{ versionInfo.current_version || '-' }}</div>
            <div class="summary-actions">
              <el-button class="check-update-btn" @click="checkRemoteUpdate" :loading="checkingRemote"
                >{{ $t('message.systemUpgrade.checkUpdates') }}</el-button
              >
            </div>
          </div>
        </div>

        <!-- 升级进度展示 -->
        <div v-if="downloadStage !== 'idle' && !this.showUpgradeComplete" class="upgrade-section">
          <div class="section-title">
            {{ $t('message.systemUpgrade.upgradeProgress') }}
            <el-button type="text" class="ml8" @click="handleRefreshStatus">{{ $t('message.systemUpgrade.refreshStatus') }}</el-button>
          </div>
          <!-- 顶部进度条（加粗蓝色） -->
          <el-progress :percentage="downloadProgress" :show-text="false" :stroke-width="10" class="progress-line" />
          <!-- 分段状态行 -->
          <div class="progress-status-row">
            <div class="status-item">
              <i
                :class="
                  downloadSteps[0].status === 'loading'
                    ? 'el-icon-loading status-loading'
                    : downloadSteps[0].status === 'success'
                    ? 'el-icon-circle-check status-ok'
                    : 'el-icon-time status-muted'
                "
              ></i>
              <span
                :class="
                  downloadSteps[0].status === 'loading'
                    ? 'status-loading'
                    : downloadSteps[0].status === 'success'
                    ? 'status-ok'
                    : 'status-muted'
                "
                >{{ $t('message.systemUpgrade.check') }}</span
              >
            </div>
            <div class="status-item">
              <i
                :class="
                  downloadSteps[1].status === 'loading'
                    ? 'el-icon-loading status-loading'
                    : downloadSteps[1].status === 'success'
                    ? 'el-icon-circle-check status-ok'
                    : 'el-icon-time status-muted'
                "
              ></i>
              <span
                :class="
                  downloadSteps[1].status === 'loading'
                    ? 'status-loading'
                    : downloadSteps[1].status === 'success'
                    ? 'status-ok'
                    : 'status-muted'
                "
                >{{ $t('message.systemUpgrade.backup') }}</span
              >
            </div>
            <div class="status-item">
              <i
                :class="
                  downloadSteps[2].status === 'loading'
                    ? 'el-icon-loading status-loading'
                    : downloadSteps[2].status === 'success'
                    ? 'el-icon-circle-check status-ok'
                    : 'el-icon-time status-muted'
                "
              ></i>
              <span
                :class="
                  downloadSteps[2].status === 'loading'
                    ? 'status-loading'
                    : downloadSteps[2].status === 'success'
                    ? 'status-ok'
                    : 'status-muted'
                "
                >{{ $t('message.systemUpgrade.download') }}</span
              >
            </div>
            <div class="status-item">
              <i
                :class="
                  downloadSteps[3].status === 'loading'
                    ? 'el-icon-loading status-loading'
                    : downloadSteps[3].status === 'success'
                    ? 'el-icon-circle-check status-ok'
                    : 'el-icon-time status-muted'
                "
              ></i>
              <span
                :class="
                  downloadSteps[3].status === 'loading'
                    ? 'status-loading'
                    : downloadSteps[3].status === 'success'
                    ? 'status-ok'
                    : 'status-muted'
                "
                >{{ downloadType === 4 ? $t('message.systemUpgrade.completed') : $t('message.systemUpgrade.updating') }}</span
              >
            </div>
            <!-- <div class="status-item">
              <span
                :class="downloadType === 4 ? 'status-ok' : downloadStage === 'error' ? 'status-error' : 'status-muted'"
                >{{ downloadType === 4 ? '完成' : '更新中' }}</span
              >
            </div> -->
          </div>
          <div class="mt10 download-msg">{{ downloadMessage }}</div>
        </div>
        <!-- 文件校验失败提示 -->
        <div class="upgrade-section" v-if="downloadType >= 0 && downloadSteps[0].status !== 'loading' && !this.showUpgradeComplete">
          <div class="section-title">
            <span class="step-num">1</span>
            <span>{{ $t('message.systemUpgrade.check') }}</span>
          </div>
          <template v-if="downloadSteps[0].status === 'error'">
            <div class="check-error-box mt16">
              <div class="check-error-desc">
                {{ downloadSteps[0].message || $t('message.systemUpgrade.checkFailedTip') }}
              </div>
            </div>
            <!-- 变更文件列表 -->
            <div v-if="normalizedCheckErrorFiles.length && downloadType === 0" class="mt16">
              <div class="error-files-table-wrap">
                <el-table :data="normalizedCheckErrorFiles" size="small" class="error-files-table" :max-height="325">
                  <el-table-column type="index" :label="$t('message.systemUpgrade.serialNumber')" width="120"></el-table-column>
                  <el-table-column prop="path" :label="$t('message.systemUpgrade.filePath')" min-width="360" show-overflow-tooltip></el-table-column>
                </el-table>
              </div>
            </div>
            <div class="check-error-actions mt10" v-if="downloadType === 0">
              <el-button size="small" type="primary" @click="cancelUpgrade">{{ $t('message.systemUpgrade.cancelUpgrade') }}</el-button>
              <el-button size="small" class="ml8" @click="ignoreAndProceed(0)">{{ $t('message.systemUpgrade.ignoreAndProceed') }}</el-button>
            </div>
          </template>
          <div v-else class="check-success-box mt16">{{ downloadSteps[0].message || $t('message.systemUpgrade.checkCompleted') }}</div>
        </div>
        <!-- 步骤2: 备份 -->
        <div class="upgrade-section" v-if="downloadType >= 1 && downloadSteps[1].status !== 'loading' && !this.showUpgradeComplete">
          <div class="section-title">
            <span class="step-num">2</span>
            <span>{{ $t('message.systemUpgrade.backup') }}</span>
          </div>
          <div v-if="downloadSteps[1].status === 'success'" class="check-success-box mt16">
            {{ downloadSteps[1].message || $t('message.systemUpgrade.databaseBackupCompleted') }}
          </div>
          <template v-else>
            <div class="check-error-box mt16">
              <div class="check-error-desc">
                {{ downloadSteps[1].message || $t('message.systemUpgrade.databaseBackupFailed') }}
              </div>
            </div>
            <div class="check-error-actions mt10">
              <el-button size="small" type="primary" @click="cancelUpgrade">{{ $t('message.systemUpgrade.cancelUpgrade') }}</el-button>
              <el-button size="small" class="ml8" @click="ignoreAndProceed(1)">{{ $t('message.systemUpgrade.ignoreAndProceed') }}</el-button>
              <el-button size="small" class="ml8" @click="reExecuteUpgrade">{{ $t('message.systemUpgrade.retryBackup') }}</el-button>
            </div>
          </template>
        </div>
        <!-- 步骤3: 执行数据库升级 -->
        <div class="upgrade-section" v-if="downloadType >= 2 && downloadSteps[2].status !== 'loading' && !this.showUpgradeComplete">
          <div class="section-title">
            <span class="step-num">3</span>
            <span>{{ $t('message.systemUpgrade.downloadUpdates') }}</span>
          </div>
          <div class="check-success-box mt16">{{ downloadSteps[2].message || $t('message.systemUpgrade.updateFileDownloadCompleted') }}</div>
        </div>
        <div
          class="upgrade-section"
          v-if="sqlExecutionLogs.length > 0 && downloadType >= 3 && downloadSteps[3].status !== 'loading' && !this.showUpgradeComplete"
        >
          <!-- 升级进度详情 -->
          <template>
            <div class="section-title">
              <span class="step-num">4</span>
              <span>{{ $t('message.systemUpgrade.executeDatabaseUpgrade') }}</span>
            </div>
            <div class="upgrade-progress-detail">
              <!-- SQL执行详情 -->
              <div class="sql-execution-logs">
                <div class="logs-header">
                  <span>{{ $t('message.systemUpgrade.sqlExecutionDetails') }}</span>
                  <el-tag size="mini" type="success">{{ $t('message.systemUpgrade.successCount') }}: {{ sqlSuccessCount }}</el-tag>
                  <el-tag size="mini" type="danger" v-if="sqlFailedCount > 0">{{ $t('message.systemUpgrade.failedCount') }}: {{ sqlFailedCount }}</el-tag>
                  <el-tag size="mini" type="info" v-if="sqlSkippedCount > 0">{{ $t('message.systemUpgrade.skippedCount') }}: {{ sqlSkippedCount }}</el-tag>
                </div>
                <div class="logs-content">
                  <div
                    v-for="(log, index) in sqlExecutionLogs"
                    :key="index"
                    class="log-item"
                    :class="{
                      'log-success': log.status === 'success',
                      'log-failed': log.status === 'failed',
                      'log-skipped': log.status === 'skipped',
                    }"
                  >
                    <span class="log-index">{{ index + 1 }}.</span>
                    <span class="log-version">[{{ log.version }}]</span>
                    <span class="log-type">[{{ getTypeName(log.type) }}]</span>
                    <span class="log-table">{{ log.table || '-' }}</span>
                    <span v-if="log.field" class="log-field">{{ log.field }}</span>
                    <el-tag
                      size="mini"
                      :type="log.status === 'success' ? 'success' : log.status === 'failed' ? 'danger' : 'info'"
                    >
                      {{ log.status === 'success' ? $t('message.systemUpgrade.success') : log.status === 'failed' ? $t('message.systemUpgrade.failed') : $t('message.systemUpgrade.skipped') }}
                    </el-tag>
                    <span v-if="log.message" class="log-message">{{ log.message }}</span>
                  </div>
                </div>
              </div>

              <!-- 升级结果 -->
              <div class="upgrade-result">
                <el-alert
                  :title="sqlFailedCount === 0 ? $t('message.systemUpgrade.upgradeSuccess') : $t('message.systemUpgrade.upgradeCompletedWithFailures')"
                  :type="sqlFailedCount === 0 ? 'success' : 'warning'"
                  :closable="false"
                  show-icon
                >
                  <template slot="default">
                    <span>{{ $t('message.systemUpgrade.executedSuccessCount', { n: sqlSuccessCount }) }}</span>
                    <span v-if="sqlFailedCount > 0"> {{ $t('message.systemUpgrade.executedFailedCount', { n: sqlFailedCount }) }}</span>
                    <span v-if="sqlSkippedCount > 0"> {{ $t('message.systemUpgrade.executedSkippedCount', { n: sqlSkippedCount }) }}</span>
                  </template>
                </el-alert>
              </div>
            </div>
          </template>
        </div>
      </div>

      <!-- 升级记录 Tab -->
      <div v-if="currentTab === 'logs'" class="upgrade-logs">
        <el-table :data="upgradeLogList" style="width: 100%" v-loading="loadingLogs">
          <el-table-column prop="title" :label="$t('message.systemUpgrade.upgradeTitle')" min-width="120" show-overflow-tooltip />
          <el-table-column :label="$t('message.systemUpgrade.version')" width="100">
            <template slot-scope="scope">
              v{{ scope.row.first_version }}.{{ scope.row.second_version }}.{{ scope.row.third_version }}
            </template>
          </el-table-column>
          <el-table-column prop="upgrade_time" :label="$t('message.systemUpgrade.upgradeTime')" width="200" />
          <el-table-column :label="$t('message.systemUpgrade.backupStatus')" min-width="150">
            <template slot-scope="scope">
              <el-tag size="mini" :type="scope.row.file_status ? 'success' : 'danger'">
                {{ $t('message.systemUpgrade.project') }}: {{ scope.row.file_status ? scope.row.package_link : $t('message.systemUpgrade.none') }}
              </el-tag><br/>
              <el-tag size="mini" :type="scope.row.data_status ? 'success' : 'danger'">
                {{ $t('message.systemUpgrade.database') }}: {{ scope.row.data_status ? scope.row.data_link : $t('message.systemUpgrade.none') }}
              </el-tag>
            </template>
          </el-table-column>
          <el-table-column prop="content" :label="$t('message.systemUpgrade.updateContent')" min-width="200" show-overflow-tooltip>
            <template slot-scope="scope">
              <span v-html="scope.row.content"></span>
            </template>
          </el-table-column>
        </el-table>

        <!-- 分页 -->
        <div class="pagination-wrap" v-if="logsTotal > 0">
          <el-pagination
            @current-change="handleLogsPageChange"
            :current-page="logsPage"
            :page-size="logsLimit"
            layout="total, prev, pager, next"
            :total="logsTotal"
          >
          </el-pagination>
        </div>
      </div>
    </el-card>

    <!-- 升级协议弹窗 -->
    <el-dialog
      :visible.sync="agreementVisible"
      :title="$t('message.systemUpgrade.systemUpgradeAgreement')"
      width="800px"
      destroy-on-close
      :close-on-click-modal="false"
      :modal="false"
      custom-class="agreement-dialog"
    >
      <div class="agreement-content" v-loading="agreementLoading">
        <div v-html="agreementContent"></div>
      </div>
      <span slot="footer" class="dialog-footer">
        <el-button @click="agreementVisible = false">{{ $t('message.systemUpgrade.disagree') }}</el-button>
        <el-button type="primary" @click="doStartDownload" :loading="startingDownload">{{ $t('message.systemUpgrade.agreeAndUpgrade') }}</el-button>
      </span>
    </el-dialog>
    <!-- 页面本地蒙层，仅在本页显示 -->
    <div v-if="agreementVisible" class="local-mask"></div>

    <!-- 上传成功弹窗 -->
    <el-dialog
      :visible.sync="uploadSuccessVisible"
      width="400px"
      :show-close="false"
      :close-on-click-modal="false"
      custom-class="upload-success-dialog"
    >
      <div class="upload-success-content">
        <i class="el-icon-circle-check" style="font-size: 48px; color: #67c23a; margin-bottom: 16px"></i>
        <div class="success-title">{{ $t('message.systemUpgrade.uploadSuccess') }}</div>
        <div class="success-desc">
          {{ $t('message.systemUpgrade.uploadSuccessTip') }}<a href="https://mp.weixin.qq.com" target="_blank" style="color: #409eff"
            >{{ $t('message.systemUpgrade.clickToPublish') }}</a
          >
        </div>
        <el-button type="primary" @click="handleUploadSuccessClose" style="margin-top: 24px; width: 120px"
          >{{ $t('message.systemUpgrade.gotIt') }}</el-button
        >
      </div>
    </el-dialog>
  </div>
</template>

<script>
import {
  upgradeStatusApi,
  upgradeableListApi,
  downloadApi,
  downloadProgressApi,
  checkCrossVersionUpgradeApi,
  upgradeLogListApi,
  upgradeAgreementApi,
  reExecuteUpgradeApi,
} from '@/api/system';
import { routineCIUpload } from '@/api/app';
import setting from '@/setting';

export default {
  name: 'CrossVersionUpgrade',
  data() {
    return {
      currentTab: 'upgrade',
      versionInfo: {},
      // 远程检测
      checkingRemote: false,
      remoteStatus: {
        status: -1, // -1未检测, 0无更新, 1有更新
        title: '',
        force_reminder: 0,
      },
      upgradeablePackage: null, // 可升级包信息
      // 下载状态
      startingDownload: false,
      downloadStage: 'idle', // idle，loading，error，success, complete
      downloadType: -1, // 0:检测，1:备份，2:下载，3:更新
      downloadSteps: [
        { status: 'loading', progress: 0, message: '', errorFiles: [] }, // 检测
        { status: 'loading', progress: 0, message: '', errorFiles: [] }, // 备份
        { status: 'loading', progress: 0, message: '', errorFiles: [] }, // 下载
        { status: 'loading', progress: 0, message: '', errorFiles: [] }, // 升级
      ],
      downloadProgress: 0,
      downloadMessage: '',
      downloadTimer: null,
      checkErrorFiles: [],
      mpVersionData: null, // 小程序版本数据
      // 本地版本检测
      localCheckResult: null,
      pendingSqlList: [],
      // 数据库升级
      upgrading: false,
      upgradeProgress: 0,
      upgradeProgressStatus: '',
      sqlExecutionLogs: [],
      sqlSuccessCount: 0,
      sqlFailedCount: 0,
      sqlSkippedCount: 0,
      // 升级记录
      upgradeLogList: [],
      loadingLogs: false,
      logsPage: 1,
      logsLimit: 15,
      logsTotal: 0,
      // 升级协议
      agreementVisible: false,
      agreementLoading: false,
      agreementContent: '',
      uploadSuccessVisible: false,
      showUpgradeComplete: false,
      uploadingMini: false,
    };
  },
  mounted() {
    this.initPage();
  },
  beforeDestroy() {
    this.clearDownloadTimer();
  },
  computed: {
    normalizedCheckErrorFiles() {
      const list = Array.isArray(this.checkErrorFiles) ? this.checkErrorFiles : [];
      return list.map((item) => {
        if (typeof item === 'string') {
          return { path: item, note: '' };
        } else if (item && typeof item === 'object') {
          return {
            path: item.path || item.full_path || item.filename || item.file || item.name || JSON.stringify(item),
            note: item.note || item.desc || '',
          };
        } else {
          return { path: String(item), note: '' };
        }
      });
    },
  },
  methods: {
    // Tab 切换
    handleTabClick() {
      if (this.currentTab === 'logs') {
        this.loadUpgradeLogs();
      }
    },

    // 页面初始化
    async initPage() {
      // 同时检测远程更新和本地版本
      await Promise.all([this.checkLocalVersion()]);
    },

    // 检测远程更新
    async checkRemoteUpdate() {
      this.checkingRemote = true;
      try {
        const res = await upgradeStatusApi();
        this.remoteStatus = {
          status: res.data.status || 0,
          title: res.data.title || '',
          force_reminder: res.data.force_reminder || 0,
        };

        // 如果有更新，获取可升级包列表
        if (res.data.status === 1) {
          await this.loadUpgradeableList();
        }
      } catch (err) {
        this.$message.error(err.msg || this.$t('message.systemUpgrade.checkRemoteUpdateFailed'));
      } finally {
        this.checkingRemote = false;
      }
    },

    // 获取可升级包列表
    async loadUpgradeableList() {
      try {
        const res = await upgradeableListApi();
        const list = res.data?.list || res.data || [];
        if (list.length > 0) {
          this.upgradeablePackage = list[0]; // 取第一个
        }
      } catch (err) {
        console.error(this.$t('message.systemUpgrade.loadUpgradeableListFailed'), err);
      }
    },

    // 检测本地版本
    async checkLocalVersion() {
      try {
        const res = await checkCrossVersionUpgradeApi();
        this.localCheckResult = res.data;
        this.versionInfo = {
          current_version: res.data.current_version,
          current_version_main: res.data.current_version_main,
          current_code: res.data.current_code,
        };
      } catch (err) {
        console.error(this.$t('message.systemUpgrade.checkLocalVersionFailed'), err);
      }
    },

    // 开始下载更新包
    async startDownload() {
      // 打开协议弹窗
      await this.openAgreement();
    },

    async openAgreement() {
      try {
        this.agreementLoading = true;
        const res = await upgradeAgreementApi();
        this.agreementContent = res.data?.content || res.data || '';
        this.agreementVisible = true;
      } catch (err) {
        this.$message.error(err.msg || this.$t('message.systemUpgrade.getAgreementFailed'));
      } finally {
        this.agreementLoading = false;
      }
    },

    async doStartDownload() {
      if (!this.upgradeablePackage?.package_key) {
        this.$message.error(this.$t('message.systemUpgrade.noUpgradeablePackage'));
        return;
      }

      // 先关闭协议弹窗，显示页面内容
      this.agreementVisible = false;
      this.downloadStage = 'loading';

      this.startingDownload = true;
      try {
        await downloadApi(this.upgradeablePackage.package_key);
        this.downloadType = 0;
        this.startDownloadProgressPolling();
      } catch (err) {
        this.$message.error(err.msg || this.$t('message.systemUpgrade.startDownloadFailed'));
        this.downloadStage = 'error';
        this.downloadMessage = err.msg || this.$t('message.systemUpgrade.startDownloadFailed');
      } finally {
        this.startingDownload = false;
      }
    },

    // 轮询下载进度
    startDownloadProgressPolling() {
      this.downloadTimer = setInterval(async () => {
        try {
          const res = await downloadProgressApi({ type: this.downloadType });
          if (res.data) {
            const currentStage = res.data.stage || 'idle';
            const currentProgress = res.data.progress || 0;
            const currentMessage = res.data.message || '';
            const currentErrorFiles = res.data.data || [];

            this.downloadStage = currentStage;
            this.downloadProgress = currentProgress;
            this.downloadMessage = currentMessage;
            this.checkErrorFiles = currentErrorFiles;

            // 更新当前步骤状态
            if (this.downloadType >= 0 && this.downloadType < this.downloadSteps.length) {
              this.$set(this.downloadSteps, this.downloadType, {
                status: currentStage,
                progress: currentProgress,
                message: currentMessage,
                errorFiles: currentErrorFiles,
              });
            }
            if (res.data.stage === 'success' && this.downloadType === 3) {
              this.sqlExecutionLogs = res.data.data.sql_logs;
              this.sqlSuccessCount = res.data.data.executed || 0;
              this.sqlSkippedCount = res.data.data.skipped || 0;
              this.sqlFailedCount = res.data.data.failed || 0;
            }
            // 完成时停止轮询并自动刷新页面
            if (res.data.stage === 'complete' && this.downloadType === 4) {
              this.mpVersionData = res.data.routine_upload_data;
              this.clearDownloadTimer();
              this.checkLocalVersion();
              this.showUpgradeComplete = true;
            }

            // 失败时停止轮询
            if (['error'].includes(res.data.stage)) {
              this.clearDownloadTimer();
            }
            if (['success'].includes(res.data.stage) && this.downloadType !== 4) {
              this.clearDownloadTimer();
              // 标记当前步骤完成
              if (this.downloadType >= 0 && this.downloadType < this.downloadSteps.length) {
                this.$set(this.downloadSteps[this.downloadType], 'status', 'success');
              }
              this.downloadType++;
              this.startDownloadProgressPolling();
            }
          }
        } catch (err) {
          console.error(this.$t('message.systemUpgrade.getDownloadProgressFailed'), err);
        }
      }, 2000);
    },

    clearDownloadTimer() {
      if (this.downloadTimer) {
        clearInterval(this.downloadTimer);
        this.downloadTimer = null;
      }
    },

    // 重试下载
    retryDownload() {
      this.downloadStage = 'idle';
      this.downloadProgress = 0;
      this.downloadMessage = '';
    },

    // 刷新页面
    refreshPage() {
      window.location.reload();
    },
    // 取消升级，返回初始状态
    cancelUpgrade() {
      this.clearDownloadTimer();
      this.downloadStage = 'idle';
      this.downloadProgress = 0;
      this.downloadMessage = '';
      this.checkErrorFiles = [];
      this.remoteStatus.status = -1;
    },
    // 忽略并执行（尝试继续）
    async ignoreAndProceed(type) {
      if (type === 0) {
        this.normalizedCheckErrorFiles = [];
        this.downloadSteps[0].message = this.$t('message.systemUpgrade.ignoredAndProceeded');
        this.downloadSteps[0].status = 'success';
        this.downloadMessage = this.$t('message.systemUpgrade.ignoredAndProceeding');
      }
      this.downloadType += 1;
      this.startDownloadProgressPolling();
    },
    // 重新执行升级
    async reExecuteUpgrade() {
      try {
        await reExecuteUpgradeApi();
        this.startDownloadProgressPolling();
      } catch (err) {
        this.$message.error(err.msg || this.$t('message.systemUpgrade.reExecuteUpgradeFailed'));
      }
    },
    // 判断阶段是否完成
    isStageComplete(stage) {
      const stageOrder = ['idle', 'error', 'loading', 'complete', 'success'];
      const currentIndex = stageOrder.indexOf(this.downloadStage);
      const checkIndex = stageOrder.indexOf(stage);
      return currentIndex > checkIndex;
    },

    // 获取步骤图标
    getStepIcon(stage) {
      if (this.isStageComplete(stage)) return 'el-icon-success';
      if (this.downloadStage === stage) return 'el-icon-loading';
      return 'el-icon-time';
    },
    // 刷新状态
    handleRefreshStatus() {
      location.reload();
    },

    // 加载升级记录
    async loadUpgradeLogs() {
      this.loadingLogs = true;
      try {
        const res = await upgradeLogListApi({ page: this.logsPage, limit: this.logsLimit });
        this.upgradeLogList = res.data.list || [];
        this.logsTotal = res.data.count || 0;
      } catch (err) {
        this.$message.error(err.msg || this.$t('message.systemUpgrade.loadUpgradeLogsFailed'));
      } finally {
        this.loadingLogs = false;
      }
    },

    handleLogsPageChange(page) {
      this.logsPage = page;
      this.loadUpgradeLogs();
    },

    // SQL类型名称
    getTypeName(type) {
      const typeMap = {
        1: this.$t('message.systemUpgrade.createTable'),
        2: this.$t('message.systemUpgrade.dropTable'),
        3: this.$t('message.systemUpgrade.addField'),
        4: this.$t('message.systemUpgrade.modifyField'),
        5: this.$t('message.systemUpgrade.deleteField'),
        6: this.$t('message.systemCommon.addData'),
        7: this.$t('message.systemUpgrade.modifyData'),
        8: this.$t('message.systemUpgrade.deleteData'),
        '-1': this.$t('message.systemUpgrade.executeSql'),
      };
      return typeMap[type] || this.$t('message.systemUpgrade.unknown');
    },

    getTypeTagType(type) {
      const typeColorMap = {
        1: 'success',
        2: 'danger',
        3: 'primary',
        4: 'warning',
        5: 'danger',
        6: 'success',
        7: 'warning',
        8: 'danger',
        '-1': 'info',
      };
      return typeColorMap[type] || 'info';
    },

    // 上传小程序
    async handleUploadMini() {
      this.uploadingMini = true;
      try {
        await routineCIUpload({
          version: this.mpVersionData.version,
          desc: this.mpVersionData.desc,
          is_live: this.mpVersionData.is_live,
        });
        this.uploadSuccessVisible = true;
      } catch (err) {
        this.$message.error(err.msg || this.$t('message.systemUpgrade.uploadMiniProgramFailed'));
        this.$router.push({ path: `${setting.routePre}/app/routine/ci_upload` });
      } finally {
        this.uploadingMini = false;
      }
    },

    handleUploadSuccessClose() {
      this.uploadSuccessVisible = false;
      this.handleRefreshStatus();
    },
  },
};
</script>

<style lang="scss" scoped>
.upload-success-content {
  display: flex;
  flex-direction: column;
  align-items: center;
  padding: 20px 0;

  .success-title {
    font-size: 18px;
    font-weight: 500;
    color: #303133;
    margin-bottom: 8px;
  }

  .success-desc {
    font-size: 14px;
    color: #606266;
  }
}
.cross-version-upgrade {
  .new-version-summary {
    padding: 24px 0;
    background: #fff;
    border-radius: 8px;
    margin-top: 16px;
    margin-bottom: 16px;

    .summary-header {
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      gap: 0;
      margin-top: 12px;
      margin-bottom: 12px;
    }

    .summary-icon-wrap {
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .summary-icon-img {
      width: 36px;
      height: 36px;
      display: block;
    }

    .summary-title {
      font-size: 16px;
      font-weight: 600;
      text-align: center;
      margin-top: 16px;
    }

    .summary-sub {
      color: #666;
      text-align: center;
      font-size: 12px;
      margin-top: 6px;
    }

    .summary-actions {
      display: flex;
      align-items: center;
      justify-content: center;
      margin-top: 20px;
    }

    .check-update-btn {
      color: #0256ff;
      border: 1px solid #0256ff;
      background-color: transparent;
    }

    .check-update-btn:hover,
    .check-update-btn:focus {
      color: #fff;
      background-color: #0256ff;
      border-color: #0256ff;
    }

    .summary-content {
      background: #fafafa;
      border-radius: 6px;
      padding: 16px;
      margin-top: 48px;
    }

    .content-title {
      font-weight: 500;
      font-size: 14px;
      color: #333;
      padding-bottom: 8px;
      border-bottom: 1px solid #eaeaea;
      margin-bottom: 12px;
    }

    .release-time {
      color: #666;
      font-size: 12px;
      margin-left: 8px;
    }

    .content-desc {
      white-space: pre-wrap;
      font-size: 12px;
      line-height: 18px;
      color: #333;
    }
  }

  .agreement-content {
    max-height: 60vh;
    overflow: auto;
    font-size: 12px;
    font-weight: 400;
    line-height: 20px;
    color: #333333;
  }

  :deep(.agreement-dialog) {
    .el-dialog__header {
      text-align: center;
    }

    .el-dialog__title {
      font-size: 16px !important;
      line-height: 16px !important;
      font-weight: 500;
      color: #333333;
    }
  }

  .local-mask {
    position: fixed;
    inset: 0;
    background: rgba(0, 0, 0, 0.45);
    z-index: 1998;
  }

  .progress-line :deep(.el-progress-bar__inner) {
    background-color: var(--prev-color-primary);
  }

  .progress-status-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-top: 8px;
    font-size: 12px;
  }

  .status-item {
    display: flex;
    align-items: center;
    gap: 6px;
  }

  .status-ok {
    color: var(--prev-color-primary);
  }

  .status-loading {
    color: #ff8a00;
  }

  .status-error {
    color: #ff4d4f;
  }

  .status-muted {
    color: #999;
  }

  .check-error-box {
    border: 1px solid #ffd591;
    background: #fff7e6;
    border-radius: 6px;
    padding: 12px;
    color: #ad6800;
  }

  .check-error-head {
    display: flex;
    align-items: center;
    gap: 6px;
    font-weight: 500;
  }

  .check-error-desc {
    font-size: 12px;
    font-weight: 400;
  }

  .check-error-actions {
    display: flex;
    align-items: center;
  }
  .check-success-box {
    border: 1px solid rgba(2, 86, 255, 1);
    background: #e8fdf5;
    border-radius: 6px;
    padding: 12px;
    background: #f0f5ff;
    color: rgba(51, 51, 51, 1);
  }
  .mt16 {
    margin-top: 16px;
  }

  align-items: center;

  .version-info {
    display: flex;
    align-items: center;
    gap: 30px;

    .current-version {
      display: flex;
      align-items: center;
      font-size: 16px;

      .label {
        color: #666;
      }

      .version-num {
        font-weight: bold;
        color: var(--prev-color-primary);
      }
    }
  }
}

.upgrade-section {
  margin-bottom: 16px;
  padding: 16px;
  background: #f9fafc;

  .section-title {
    display: flex;
    align-items: center;
    margin-bottom: 16px;
    font-size: 16px;
    font-weight: 500;

    .step-num {
      width: 24px;
      height: 24px;
      background: var(--prev-color-primary);
      color: #fff;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 14px;
      margin-right: 10px;
    }
  }

  .section-content {
    .status-text {
      margin-left: 15px;
      color: #666;
    }
  }
}

.download-progress {
  .progress-info {
    display: flex;
    justify-content: space-between;
    margin-bottom: 8px;

    .stage-text {
      color: #409eff;
      font-weight: 500;
    }

    .progress-text {
      color: #999;
    }
  }

  .progress-steps {
    display: flex;
    justify-content: space-between;
    margin-top: 16px;

    .step-item {
      display: flex;
      align-items: center;
      gap: 6px;
      color: #999;
      font-size: 13px;

      i {
        font-size: 16px;
      }

      &.active {
        color: #409eff;

        i {
          color: #409eff;
        }
      }

      &.done {
        color: #67c23a;

        i {
          color: #67c23a;
        }
      }
    }
  }
}

.download-complete,
.download-error {
  margin-top: 10px;
}

.upgrade-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 16px;
  font-weight: 500;
}

.sql-list {
  margin-bottom: 20px;
}

.upgrade-progress-detail {
  

  .progress-header {
    margin-bottom: 20px;
  }

  .sql-execution-logs {
    margin-top: 20px;
    background: #f5f7fa;
    border-radius: 4px;
    padding: 12px;
    max-height: 300px;
    overflow-y: auto;

    .logs-header {
      display: flex;
      align-items: center;
      gap: 10px;
      margin-bottom: 10px;
      font-weight: 500;
      padding-bottom: 10px;
      border-bottom: 1px solid #eee;
    }

    .logs-content {
      font-size: 12px;
      font-family: monospace;

      .log-item {
        padding: 4px 8px;
        border-radius: 2px;
        margin-bottom: 2px;
        display: flex;
        align-items: center;
        gap: 8px;

        &.log-success {
          background: #f0f9eb;
        }

        &.log-failed {
          background: #fef0f0;
        }

        &.log-skipped {
          background: #f4f4f5;
        }

        .log-index {
          color: #999;
          min-width: 30px;
        }

        .log-version {
          color: #409eff;
        }

        .log-type {
          color: #e6a23c;
          margin-right: 4px;
        }

        .log-table {
          color: #606266;
          min-width: 100px;
        }

        .log-field {
          color: #67c23a;
          margin-right: 8px;
        }

        .log-message {
          color: #909399;
          font-size: 11px;
          margin-left: auto;
        }
      }
    }
  }

  .upgrade-result {
    margin-top: 20px;
  }
}

.upgrade-logs {
  .pagination-wrap {
    margin-top: 20px;
    display: flex;
    justify-content: flex-end;
  }
}

/* 错误文件列表：每行高度 30px */
.error-files-table-wrap {
  max-height: none;
  overflow: visible;
}

.error-files-table :deep(.el-table__cell) {
  padding-top: 0 !important;
  padding-bottom: 0 !important;
}

.error-files-table :deep(.el-table__cell .cell) {
  line-height: 30px;
  padding-top: 0;
  padding-bottom: 0;
}

.download-msg {
  font-size: 12px;
  font-weight: 400;
  color: #999;
}
</style>
