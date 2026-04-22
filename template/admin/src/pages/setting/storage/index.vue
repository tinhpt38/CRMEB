<template>
  <div>
    <div class="message">
      <el-card :bordered="false" shadow="never" :body-style="{ padding: '0 20px 20px' }">
        <div class="">
          <el-tabs v-model="currentTab" @tab-click="changeTab">
            <el-tab-pane
              :label="item.label"
              :name="item.value.toString()"
              v-for="(item, index) in headerList"
              :key="index"
            />
          </el-tabs>
        </div>
        <el-alert closable v-if="currentTab == 1">
          <template slot="title">
            <p>{{ $t('message.setting.storage.alertThumb1') }}</p>
            <p>{{ $t('message.setting.storage.alertThumb2') }}</p>
            <p>{{ $t('message.setting.storage.alertThumb3') }}</p>
            <p>{{ $t('message.setting.storage.alertThumb4') }}</p>
          </template>
        </el-alert>
        <el-alert closable v-else>
          <template slot="title">
            <p v-if="currentTab == 2">
              {{ $t('message.setting.storage.qiniuGuide') }}：<a href="https://doc.crmeb.com/single/v5/7792" target="_blank">{{ $t('message.setting.storage.clickToView') }}</a>
            </p>
            <p v-if="currentTab == 3">
              {{ $t('message.setting.storage.aliyunGuide') }}：<a href="https://doc.crmeb.com/single/v5/7790" target="_blank">{{ $t('message.setting.storage.clickToView') }}</a>
            </p>
            <p v-if="currentTab == 4">
              {{ $t('message.setting.storage.tencentGuide') }}：<a href="https://doc.crmeb.com/single/v5/7791" target="_blank">{{ $t('message.setting.storage.clickToView') }}</a>
            </p>
            <p v-if="currentTab == 5">
              {{ $t('message.setting.storage.jdGuide') }}：<a href="https://doc.crmeb.com/single/v5/8522" target="_blank">{{ $t('message.setting.storage.clickToView') }}</a>
            </p>
            <p v-if="currentTab == 6">
              {{ $t('message.setting.storage.huaweiGuide') }}：<a href="https://doc.crmeb.com/single/v5/8523" target="_blank">{{ $t('message.setting.storage.clickToView') }}</a>
            </p>
            <p v-if="currentTab == 7">
              {{ $t('message.setting.storage.tianyiGuide') }}：<a href="https://doc.crmeb.com/single/v5/8524" target="_blank">{{ $t('message.setting.storage.clickToView') }}</a>
            </p>
            <p>{{ $t('message.setting.storage.step1') }}</p>
            <p>{{ $t('message.setting.storage.step2') }}</p>
            <template v-if="currentTab == 2">
              <p>{{ $t('message.setting.storage.step3Required') }}</p>
              <p>{{ $t('message.setting.storage.step4Required') }}</p>
            </template>
            <template v-else>
              <p>{{ $t('message.setting.storage.step3Optional') }}</p>
              <p>{{ $t('message.setting.storage.step4Optional') }}</p>
            </template>
          </template>
        </el-alert>
      </el-card>
    </div>
    <div class="pt16" v-if="currentTab == 1">
      <el-card :bordered="false" shadow="never" class="ivu-mt">
        <el-row>
          <el-col :span="24">
            <span class="save-type"> {{ $t('message.setting.storage.storageType') }}： </span>
            <el-radio-group v-model="formValidate.upload_type" @input="changeSave">
              <el-radio label="1">{{ $t('message.setting.storage.localStorage') }}</el-radio>
              <el-radio label="2">{{ $t('message.setting.storage.qiniuStorage') }}</el-radio>
              <el-radio label="3">{{ $t('message.setting.storage.aliyunStorage') }}</el-radio>
              <el-radio label="4">{{ $t('message.setting.storage.tencentStorage') }}</el-radio>
              <el-radio label="5">{{ $t('message.setting.storage.jdStorage') }}</el-radio>
              <el-radio label="6">{{ $t('message.setting.storage.huaweiStorage') }}</el-radio>
              <el-radio label="7">{{ $t('message.setting.storage.tianyiStorage') }}</el-radio>
            </el-radio-group>
            <!-- <el-switch :active-value="1"  :inactive-value="0"
              v-model="localStorage"
              size="large"
              @change="addSwitch"
            >
              <span slot="open">{{ $t('message.systemCommon.enabled') }}</span>
              <span slot="close">{{ $t('message.systemCommon.disabled') }}</span>
             </el-switch> -->
          </el-col>
        </el-row>
      </el-card>
      <el-card :bordered="false" shadow="never" class="ivu-mt">
        <el-form ref="formValidate" :model="formValidate" :rules="ruleValidate">
          <div class="abbreviation">
            <el-form-item :label="$t('message.setting.storage.enableThumbnail') + '：'" label-width="110px">
              <el-switch
                :active-value="1"
                :inactive-value="0"
                v-model="formValidate.image_thumb_status"
                size="large"
              >
                <span slot="open">{{ $t('message.systemCommon.enabled') }}</span>
                <span slot="close">{{ $t('message.systemCommon.disabled') }}</span>
              </el-switch>
            </el-form-item>
            <div class="top" v-if="formValidate.image_thumb_status == 1">
              <div class="topBox">
                <div class="topLeft">
                  <div class="img">
                    <img class="imgs" src="../../../assets/images/abbreviationBig.png" alt="" />
                  </div>
                  <div>{{ $t('message.setting.storage.thumbLarge') }}</div>
                </div>
                <div class="topRight">
                  <el-form-item :label="$t('message.setting.storage.width') + '：'">
                    <el-input
                      class="topIput"
                      type="number"
                      v-model="formValidate.thumb_big_width"
                      :placeholder="$t('message.setting.enterWidth')"
                    >
                      <span slot="append">px</span>
                    </el-input>
                  </el-form-item>
                  <el-form-item :label="$t('message.setting.storage.height') + '：'">
                    <el-input
                      class="topIput"
                      type="number"
                      v-model="formValidate.thumb_big_height"
                      :placeholder="$t('message.setting.enterHeight')"
                    >
                      <span slot="append">px</span>
                    </el-input>
                  </el-form-item>
                </div>
              </div>
              <div class="topBox">
                <div class="topLeft">
                  <div class="img">
                    <img class="imgs" src="../../../assets/images/abbreviation.png" alt="" />
                  </div>
                  <div>{{ $t('message.setting.storage.thumbMedium') }}</div>
                </div>
                <div class="topRight">
                  <el-form-item :label="$t('message.setting.storage.width') + '：'">
                    <el-input
                      class="topIput"
                      type="number"
                      v-model="formValidate.thumb_mid_width"
                      :placeholder="$t('message.setting.enterWidth')"
                    >
                      <span slot="append">px</span>
                    </el-input>
                  </el-form-item>
                  <el-form-item :label="$t('message.setting.storage.height') + '：'">
                    <el-input
                      type="number"
                      class="topIput"
                      v-model="formValidate.thumb_mid_height"
                      :placeholder="$t('message.setting.enterHeight')"
                    >
                      <span slot="append">px</span>
                    </el-input>
                  </el-form-item>
                </div>
              </div>
              <div class="topBox">
                <div class="topLeft">
                  <div class="img">
                    <img class="imgs" src="../../../assets/images/abbreviationSmall.png" alt="" />
                  </div>
                  <div>{{ $t('message.setting.storage.thumbSmall') }}</div>
                </div>
                <div class="topRight">
                  <el-form-item :label="$t('message.setting.storage.width') + '：'">
                    <el-input
                      class="topIput"
                      type="number"
                      v-model="formValidate.thumb_small_width"
                      :placeholder="$t('message.setting.enterWidth')"
                    >
                      <span slot="append">px</span>
                    </el-input>
                  </el-form-item>
                  <el-form-item :label="$t('message.setting.storage.height') + '：'">
                    <el-input
                      class="topIput"
                      type="number"
                      v-model="formValidate.thumb_small_height"
                      :placeholder="$t('message.setting.enterHeight')"
                    >
                      <span slot="append">px</span>
                    </el-input>
                  </el-form-item>
                </div>
              </div>
            </div>
            <el-divider />
            <div class="content mt20">
            <el-form-item :label="$t('message.setting.storage.enableWatermark') + '：'" label-width="110px">
                <el-switch
                  :active-value="1"
                  :inactive-value="0"
                  v-model="formValidate.image_watermark_status"
                  size="large"
                >
                  <span slot="open">{{ $t('message.systemCommon.enabled') }}</span>
                  <span slot="close">{{ $t('message.systemCommon.disabled') }}</span>
                </el-switch>
              </el-form-item>
              <div v-if="formValidate.image_watermark_status == 1">
                <el-form-item :label="$t('message.systemCommon.type') + '：'" label-width="110px">
                  <el-radio-group v-model="formValidate.watermark_type">
                    <el-radio :label="1">{{ $t('message.setting.storage.image') }}</el-radio>
                    <el-radio :label="2">{{ $t('message.setting.storage.text') }}</el-radio>
                  </el-radio-group>
                </el-form-item>
                <div v-if="formValidate.watermark_type == 1">
                  <div class="flex">
                    <el-form-item class="contentIput" :label="$t('message.setting.storage.opacity') + '：'" prop="name" label-width="110px">
                      <el-input
                        class="topIput"
                        type="number"
                        v-model="formValidate.watermark_opacity"
                        :placeholder="$t('message.setting.enterWatermarkOpacity')"
                      >
                      </el-input>
                    </el-form-item>
                    <el-form-item class="contentIput" :label="$t('message.setting.storage.tilt') + '：'" prop="mail" label-width="110px">
                      <el-input
                        class="topIput"
                        type="number"
                        v-model="formValidate.watermark_rotate"
                        :placeholder="$t('message.setting.enterWatermarkTilt')"
                      >
                      </el-input>
                    </el-form-item>
                  </div>
                  <div class="flex">
                    <el-form-item class="contentIput" :label="$t('message.setting.storage.image') + '：'" prop="name" label-width="110px">
                      <div class="picBox" v-db-click @click="modalPicTap($t('message.systemCommon.selectSingle'))">
                        <div class="pictrue" v-if="formValidate.watermark_image">
                          <img :src="formValidate.watermark_image" />
                        </div>
                        <div class="upLoad acea-row row-center-wrapper" v-else>
                          <i class="el-icon-picture-outline" style="font-size: 24px"></i>
                        </div>
                      </div>
                    </el-form-item>
                    <el-form-item class="contentIput" :label="$t('message.setting.storage.position') + '：'" prop="mail" label-width="110px">
                      <div class="conents">
                        <div class="positionBox">
                          <div
                            class="topIput box"
                            :class="positionId == item.id ? 'on' : ''"
                            v-for="(item, index) in boxs"
                            :key="index"
                            v-db-click
                            @click="bindbox(item)"
                          ></div>
                        </div>
                        <div class="title">{{ positiontlt }}</div>
                      </div>
                    </el-form-item>
                  </div>
                  <div class="flex">
                    <el-form-item class="contentIput" :label="$t('message.setting.storage.offsetX') + '：'" label-width="110px" prop="name">
                      <el-input
                        class="topIput"
                        type="number"
                        v-model="formValidate.watermark_x"
                        :placeholder="$t('message.setting.enterWatermarkOffsetX')"
                        style="width: 240px"
                      >
                        <span slot="append">px</span>
                      </el-input>
                    </el-form-item>
                    <el-form-item class="contentIput" :label="$t('message.setting.storage.offsetY') + '：'" label-width="110px" prop="mail">
                      <el-input
                        class="topIput"
                        type="number"
                        v-model="formValidate.watermark_y"
                        :placeholder="$t('message.setting.enterWatermarkOffsetY')"
                        style="width: 240px"
                      >
                        <span slot="append">px</span>
                      </el-input>
                    </el-form-item>
                  </div>
                </div>
                <!-- 水印类型为文字 -->
                <div v-else>
                  <div class="flex">
                    <el-form-item class="contentIput" :label="$t('message.setting.storage.text') + '：'" label-width="110px" prop="name">
                      <el-input class="topIput" v-model="formValidate.watermark_text" :placeholder="$t('message.setting.enterWatermarkText')">
                      </el-input>
                    </el-form-item>
                    <el-form-item class="contentIput" :label="$t('message.setting.storage.textSize') + '：'" label-width="110px">
                      <el-input
                        class="topIput"
                        type="number"
                        v-model="formValidate.watermark_text_size"
                        :placeholder="$t('message.setting.enterWatermarkTextSize')"
                      >
                      </el-input>
                    </el-form-item>
                  </div>
                  <div class="flex">
                    <el-form-item class="contentIput" :label="$t('message.setting.storage.fontColor') + '：'" prop="name" label-width="110px">
                      <el-color-picker v-model="formValidate.watermark_text_color"></el-color-picker>
                    </el-form-item>
                    <el-form-item class="contentIput" :label="$t('message.setting.storage.position') + '：'" prop="mail" label-width="110px">
                      <div class="conents">
                        <div class="positionBox">
                          <div
                            class="topIput box"
                            :class="positionId == item.id ? 'on' : ''"
                            v-for="(item, index) in boxs"
                            :key="index"
                            v-db-click
                            @click="bindbox(item)"
                          ></div>
                        </div>
                        <div class="title">{{ positiontlt }}</div>
                      </div>
                    </el-form-item>
                  </div>
                  <div class="flex">
                    <el-form-item class="contentIput" :label="$t('message.setting.storage.textRotation') + '：'" label-width="110px">
                      <el-input
                        class="topIput"
                        type="number"
                        v-model="formValidate.watermark_text_angle"
                        :placeholder="$t('message.setting.enterWatermarkTextAngle')"
                      >
                      </el-input>
                    </el-form-item>
                    <el-form-item class="contentIput" :label="$t('message.setting.storage.offsetX') + '：'" label-width="110px">
                      <el-input
                        class="topIput"
                        type="number"
                        v-model="formValidate.watermark_x"
                        :placeholder="$t('message.setting.enterWatermarkOffsetX')"
                      >
                        <span slot="append">px</span>
                      </el-input>
                    </el-form-item>
                  </div>
                  <el-form-item class="contentIput" :label="$t('message.setting.storage.offsetY') + '：'" prop="mail" label-width="110px">
                    <el-input
                      class="topIput"
                      type="number"
                      v-model="formValidate.watermark_y"
                      :placeholder="$t('message.setting.enterWatermarkOffsetY')"
                    >
                      <span slot="append">px</span>
                    </el-input>
                  </el-form-item>
                </div>
              </div>
            </div>
            <el-form-item>
              <el-button type="primary" v-db-click @click="handleSubmit('formValidate')">{{ $t('message.systemCommon.save') }}</el-button>
            </el-form-item>
          </div>
        </el-form>
      </el-card>
    </div>
    <!-- 缩略图配置 -->
    <div class="pt10" v-else-if="currentTab == 10"></div>
    <div class="pt10" v-else>
      <el-card :bordered="false" shadow="never" class="ivu-mt">
        <el-row class="mb20">
          <el-col :span="24">
            <el-button type="primary" v-db-click @click="addStorageBtn">{{ $t('message.setting.storage.addStorageSpace') }}</el-button>
            <el-button type="success" v-db-click @click="synchro" style="margin-left: 20px">{{ $t('message.setting.storage.syncStorageSpace') }}</el-button>
            <el-button v-db-click @click="addConfigBtn" style="float: right">{{ $t('message.setting.storage.editConfig') }}</el-button>
          </el-col>
        </el-row>
        <el-table
          :data="levelLists"
          ref="table"
          class="mt14"
          v-loading="loading"
          highlight-current-row
          :no-userFrom-text="$t('message.systemCommon.noData')"
          :no-filtered-userFrom-text="$t('message.systemCommon.noFilterResult')"
        >
          <el-table-column :label="$t('message.setting.storage.storageSpaceName')" min-width="120">
            <template slot-scope="scope">
              <span>{{ scope.row.name }}</span>
            </template>
          </el-table-column>
          <el-table-column :label="$t('message.setting.storage.region')" min-width="90">
            <template slot-scope="scope">
              <span>{{ scope.row._region }}</span>
            </template>
          </el-table-column>
          <el-table-column :label="$t('message.setting.storage.spaceDomain')" min-width="130">
            <template slot-scope="scope">
              <span>{{ scope.row.domain }}</span>
            </template>
          </el-table-column>
          <el-table-column :label="$t('message.setting.storage.usageStatus')" min-width="90">
            <template slot-scope="scope">
              <el-switch
                class="defineSwitch"
                :active-value="1"
                :inactive-value="0"
                v-model="scope.row.status"
                :value="scope.row.status"
                @change="changeSwitch(scope.row, index)"
                size="large"
                :active-text="$t('message.systemCommon.enabled')"
                :inactive-text="$t('message.systemCommon.disabled')"
              >
              </el-switch>
            </template>
          </el-table-column>
          <el-table-column :label="$t('message.systemCommon.addTime')" min-width="130">
            <template slot-scope="scope">
              <span>{{ scope.row._add_time }}</span>
            </template>
          </el-table-column>
          <el-table-column :label="$t('message.systemCommon.updateTime')" min-width="130">
            <template slot-scope="scope">
              <span>{{ scope.row._update_time }}</span>
            </template>
          </el-table-column>
          <el-table-column :label="$t('message.systemCommon.operation')" fixed="right" width="220">
            <template slot-scope="scope">
              <template v-if="scope.row.domain && scope.row.domain != scope.row.cname">
                <span class="btn" v-db-click @click="config(scope.row)">{{ $t('message.setting.storage.cnameConfig') }}</span>
                <el-divider direction="vertical"></el-divider>
              </template>
              <span class="btn" v-db-click @click="edit(scope.row)">{{ $t('message.setting.storage.editSpaceDomain') }}</span>
              <el-divider direction="vertical"></el-divider>
              <span class="btn" v-db-click @click="del(scope.row, $t('message.systemCommon.deleteThisRecord'), scope.$index)">{{ $t('message.systemCommon.delete') }}</span>
            </template>
          </el-table-column>
        </el-table>
        <div class="acea-row row-right page">
          <pagination
            v-if="total"
            :total="total"
            :page.sync="list.page"
            :limit.sync="list.limit"
            @pagination="getlist"
          />
        </div>
      </el-card>
    </div>
    <el-dialog :visible.sync="configuModal" :title="$t('message.setting.storage.cnameConfig')" width="570px">
      <div>
        <div class="confignv"><span class="configtit">{{ $t('message.setting.storage.hostRecord') }}：</span>{{ configData.domain }}</div>
        <div class="confignv"><span class="configtit">{{ $t('message.setting.storage.recordType') }}：</span>CNAME</div>
        <div class="confignv">
          <span class="configtit">{{ $t('message.setting.storage.recordValue') }}：</span>{{ configData.cname }}
          <span class="copy copy-data" v-db-click @click="insertCopy(configData.cname)">{{ $t('message.systemCommon.copy') }}</span>
        </div>
      </div>
    </el-dialog>
    <el-dialog :visible.sync="modalPic" width="950px" :title="$t('message.systemCommon.uploadProductImage')" :close-on-click-modal="false">
      <uploadPictures
        :isChoice="isChoice"
        @getPic="getPic"
        :gridBtn="gridBtn"
        :gridPic="gridPic"
        v-if="modalPic"
      ></uploadPictures>
    </el-dialog>
  </div>
</template>

<script>
import ClipboardJS from 'clipboard';
import uploadPictures from '@/components/uploadPictures';

import {
  storageConfigApi,
  addConfigApi,
  addStorageApi,
  storageListApi,
  storageSynchApi,
  storageSwitchApi,
  storageStatusApi,
  editStorageApi,
  positionInfoApi,
  positionPostApi,
  saveType,
} from '@/api/setting';
export default {
  components: { uploadPictures },
  data() {
    return {
      modalPic: false,
      saveType: 0,
      isChoice: this.$t('message.systemCommon.selectSingle'),
      gridBtn: {
        xl: 4,
        lg: 8,
        md: 8,
        sm: 8,
        xs: 8,
      },
      gridPic: {
        xl: 6,
        lg: 8,
        md: 12,
        sm: 12,
        xs: 12,
      },
      positionId: 1,
      positiontlt: '',
      formValidate: {
        image_thumb_status: 0,
        thumb_big_height: '',
        thumb_big_width: '',
        thumb_mid_width: '',
        thumb_mid_height: '',
        thumb_small_height: '',
        thumb_small_width: '',
        image_watermark_status: 0,
        watermark_type: 1,
        watermark_opacity: '',
        watermark_rotate: '',
        watermark_position: 1,
      },
      boxs: [
        { content: this.$t('message.setting.storage.topLeft'), id: 1 },
        { content: this.$t('message.setting.storage.top'), id: 2 },
        { content: this.$t('message.setting.storage.topRight'), id: 3 },
        { content: this.$t('message.setting.storage.middleLeft'), id: 4 },
        { content: this.$t('message.setting.storage.middle'), id: 5 },
        { content: this.$t('message.setting.storage.middleRight'), id: 6 },
        { content: this.$t('message.setting.storage.bottomLeft'), id: 7 },
        { content: this.$t('message.setting.storage.bottom'), id: 8 },
        { content: this.$t('message.setting.storage.bottomRight'), id: 9 },
      ],
      ruleValidate: {},
      configuModal: false,
      configData: '',
      headerList: [
        { label: this.$t('message.setting.storage.storageConfig'), value: '1' },
        { label: this.$t('message.setting.storage.qiniuStorageTab'), value: '2' },
        { label: this.$t('message.setting.storage.aliyunStorageTab'), value: '3' },
        { label: this.$t('message.setting.storage.tencentStorageTab'), value: '4' },
        { label: this.$t('message.setting.storage.jdStorageTab'), value: '5' },
        { label: this.$t('message.setting.storage.huaweiStorageTab'), value: '6' },
        { label: this.$t('message.setting.storage.tianyiStorageTab'), value: '7' },
        // { label: "缩略图配置", value: "10" },
      ],

      total: 0,
      list: {
        page: 1,
        limit: 15,
        type: '1',
      },
      levelLists: [],
      currentTab: '1',
      loading: false,
      addData: {
        input: '',
        select: '',
        jurisdiction: '1',
        type: '1',
      },
      confData: {
        AccessKeyId: '',
        AccessKeySecret: '',
      },
      localStorage: false,
    };
  },
  created() {
    storageConfigApi().then((res) => {
      if (res.data.type == 1) {
        this.localStorage = true;
      }
      this.formValidate.upload_type = res.data.type;
      this.currentTab = res.data.type.toString();
      this.changeTab();
    });
  },
  methods: {
    insertCopy(text) {
      this.$copyText(text)
        .then((message) => {
          this.$message.success(this.$t('message.systemCommon.copySuccess'));
        })
        .catch((err) => {
          this.$message.error(this.$t('message.systemCommon.copyFailed'));
        });
    },
    changeSave(type) {
      saveType(type)
        .then((res) => {
          this.$message.success(res.msg);
        })
        .catch((err) => {
          this.$message.error(err.msg);
        });
    },
    bindbox(item) {
      this.positionId = item.id;
      this.positiontlt = item.content;
      this.formValidate.watermark_position = item.id;
    },
    handleSubmit(name) {
      if (this.formValidate.image_watermark_status) {
        this.$refs[name].validate((valid) => {
          if (valid) {
            this.postMessage(this.formValidate);
          } else {
            this.$message.error('Fail!');
          }
        });
      } else {
        this.postMessage(this.formValidate);
      }
    },
    //保存接口
    postMessage(data) {
      positionPostApi(data)
        .then((res) => {
          this.$message.success(res.msg);
        })
        .catch((err) => {
          this.$message.error(err.msg);
        });
    },
    // 选择图片
    modalPicTap() {
      this.modalPic = true;
    },
    // 选中图片
    getPic(pc) {
      this.formValidate.watermark_image = pc.att_dir;
      this.modalPic = false;
    },
    config(row) {
      this.configuModal = true;
      this.configData = row;
    },
    //同步储存空间
    synchro() {
      storageSynchApi(this.currentTab)
        .then((res) => {
          this.$message.success(res.msg);
          this.getlist();
        })
        .catch((err) => {
          this.$message.error(err.msg);
        });
    },
    // 添加存储空间
    addStorageBtn() {
      this.$modalForm(addStorageApi(this.currentTab)).then(() => {
        this.getlist();
      });
    },
    // 修改配置信息
    addConfigBtn() {
      this.$modalForm(addConfigApi(this.currentTab)).then(() => {
        this.getlist();
      });
    },
    //修改空间域名
    edit(row) {
      this.$modalForm(editStorageApi(row.id)).then(() => {
        this.getlist();
      });
    },
    changeSwitch(row, item) {
      return new Promise((resolve) => {
        this.$msgbox({
          title: this.$t('message.setting.storage.switchStatus'),
          message: this.$t('message.setting.storage.confirmSwitchStatus'),
          showCancelButton: true,
          cancelButtonText: this.$t('message.systemCommon.cancel'),
          confirmButtonText: this.$t('message.systemCommon.confirm'),
          iconClass: 'el-icon-warning',
          confirmButtonClass: 'btn-custom-cancel',
        })
          .then(() => {
            storageStatusApi(row.id)
              .then((res) => {
                this.$message.success(res.msg);
                this.getlist();
              })
              .catch((err) => {
                this.$message.error(err.msg);
              });
          })
          .catch(() => {});
      });
    },
    getlist() {
      this.loading = true;
      storageListApi(this.list).then((res) => {
        this.total = res.data.count;
        this.levelLists = res.data.list;
        this.loading = false;
      });
    },
    changeTab() {
      this.list.type = this.currentTab;
      this.list.page = 1;
      if (this.currentTab == 1) {
        this.getposition();
      } else {
        this.getlist();
      }
    },
    getposition() {
      let that = this;
      positionInfoApi().then((res) => {
        this.formValidate = res.data;
        this.positionId = res.data.watermark_position;
        for (var i = 0; i < this.boxs.length; i++) {
          if (this.boxs[i].id == res.data.watermark_position) {
            that.bindbox(this.boxs[i]);
          }
        }
      });
    },
    addSwitch(e) {
      if (e) {
        this.localStorage = 1;
      }
      storageSwitchApi({ type: this.localStorage })
        .then((res) => {
          this.$message.success(res.msg);
          this.getlist();
        })
        .catch((err) => {
          this.$message.error(err.msg);
        });
    },
    // 删除
    del(row, tit, num) {
      let delfromData = {
        title: tit,
        num: num,
        url: `system/config/storage/${row.id}`,
        method: 'DELETE',
        ids: '',
      };
      this.$modalSure(delfromData)
        .then((res) => {
          this.$message.success(res.msg);
          this.getlist();
        })
        .catch((res) => {
          this.$message.error(res.msg);
        });
    },
  },
};
</script>
<style scoped lang="scss">
::v-deep .el-tabs__item {
  height: 54px !important;
  line-height: 54px !important;
}
.ivu-input-group > .ivu-input:last-child,
::v-deep .ivu-input-group-append {
  background: none;
  color: #999999;
}
::v-deep .ivu-input-group .ivu-input {
  border-right: 0px !important;
}
.content ::v-deep .ivu-form .ivu-form-item-label {
  width: 133px;
}
.topIput {
  width: 186px;
  background: #ffffff;
  border-right: 0px !important;
}
.abbreviation {
  .top {
    display: flex;
    justify-content: flex-start;
    .topBox {
      display: flex;
      .topRight {
        width: 254px;
        margin-left: 36px;
      }
      .topLeft {
        width: 94px;
        height: 94px;

        text-align: center;
        font-size: 13px;
        font-weight: 400;
        color: #000000;
        .img {
          // width: 84px;
          height: 67px;
          background: #f7fbff;
          border-radius: 4px;
          margin-bottom: 9px;
          .imgs {
            width: 70px;
            height: 51px;
            display: inline-block;
            text-align: center;
            margin-top: 8px;
          }
        }
      }
    }
  }
  .content {
    ::v-deep .ivu-form-item-label {
      width: 96px;
    }
    .flex {
      display: flex;
      justify-content: flex-start;
      // width: 400px;

      .contentIput {
        width: 400px;
      }
      .conents {
        display: flex;
        .title {
          width: 30px;
          margin-top: 70px;
          margin-left: 6px;
        }
        .positionBox {
          display: flex;
          flex-wrap: wrap;
          width: 101px;
          height: 99px;
          border-right: 1px solid #dddddd;
          .box {
            width: 33px;
            height: 33px;
            // border-radius: 4px 0px 0px 0px;
            border: 1px solid #dddddd;
            cursor: pointer;
          }
          .on {
            background: rgba(24, 144, 255, 0.1);
          }
        }
      }
    }
  }
}
</style>
<style scoped>
.message ::v-deep .ivu-table-header thead tr th {
  padding: 8px 16px;
}
.ivu-radio-wrapper {
  margin-right: 15px;
  font-size: 12px !important;
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
  cursor: pointer;
  color: #2d8cf0;
  font-size: 12px;
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
.confignv {
  margin: 10px 0px;
}
.configtit {
  display: inline-block;
  width: 90px;
  text-align: right;
}
.copy {
  padding: 3px 5px;
  border: 1px solid #cccccc;
  border-radius: 5px;
  color: #333;
  cursor: pointer;
  margin-left: 5px;
}
.copy:hover {
  border-color: #2d8cf0;
  color: #2d8cf0;
}
.picBox {
  display: inline-block;
  cursor: pointer;
}
.picBox .pictrue {
  width: 60px;
  height: 60px;
  border: 1px dotted rgba(0, 0, 0, 0.1);
  margin-right: 10px;
}

.picBox .pictrue img {
  width: 100%;
  height: 100%;
}
.picBox .upLoad {
  width: 58px;
  height: 58px;
  line-height: 58px;
  border: 1px dotted rgba(0, 0, 0, 0.1);
  border-radius: 4px;
  background: rgba(0, 0, 0, 0.02);
}
h3 {
  margin: 5px 0 15px 0;
}
.table-box p {
  margin-bottom: 10px;
}
.save-type {
  font-size: 12px;
}
</style>
