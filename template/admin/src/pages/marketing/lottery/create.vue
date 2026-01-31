<template>
  <div>
    <div class="i-layout-page-header header-title">
      <div class="fl_header">
        <el-button
          class="btn-back"
          icon="el-icon-arrow-left"
          size="small"
          type="text"
          v-db-click
          @click="$router.go(-1)"
          >{{ $t('message.pages.marketing.lottery.create.back') }}</el-button
        >
        <el-divider direction="vertical"></el-divider>
        <span class="ivu-page-header-title">{{ $route.meta.title }}</span>
      </div>
    </div>
    <el-card :bordered="false" shadow="never" class="ivu-mt" :body-style="{ padding: '0 20px 20px' }">
      <el-row class="mt30 acea-row row-middle row-center">
        <el-col :span="24" v-loading="spinShow">
          <el-form
            class="form"
            ref="formValidate"
            :rules="ruleValidate"
            :model="formValidate"
            @on-validate="validate"
            :label-width="labelWidth"
            :label-position="labelPosition"
            @submit.native.prevent
          >
            <el-row>
              <el-col :span="24">
                <el-form-item :label="$t('message.pages.marketing.lottery.create.activityType')" prop="name" label-for="name">
                  <el-radio-group v-model="formValidate.factor" @input="onClickTab">
                    <el-radio v-for="(item, index) in tabs" :label="item.type" :disabled="!!lottery_id" :key="index">{{
                      item.name
                    }}</el-radio>
                  </el-radio-group>
                </el-form-item>
              </el-col>
              <el-col :span="24">
                <el-form-item :label="$t('message.pages.marketing.lottery.create.activityName')" prop="name" label-for="name">
                  <el-input
                    :placeholder="$t('message.pages.marketing.lottery.create.inputActivityName')"
                    v-model="formValidate.name"
                    class="content_width"
                    maxlength="80"
                    show-word-limit
                  />
                </el-form-item>
              </el-col>
              <el-col :span="24">
                <el-form-item :label="$t('message.pages.marketing.lottery.create.activityTime')">
                  <div class="acea-row row-middle">
                    <el-date-picker
                      v-model="formValidate.period"
                      :editable="false"
                      type="datetimerange"
                      format="yyyy-MM-dd"
                      value-format="yyyy-MM-dd"
                      range-separator="-"
                      :start-placeholder="$t('message.pages.marketing.lottery.create.startDate')"
                      :end-placeholder="$t('message.pages.marketing.lottery.create.endDate')"
                      @change="onchangeTime"
                      style="width: 460px"
                    ></el-date-picker>
                  </div>
                </el-form-item>
              </el-col>
              <el-col :span="24">
                <el-form-item :label="$t('message.pages.marketing.lottery.create.attendsUser')" prop="attends_user" label-for="attends_user">
                  <el-radio-group element-id="attends_user" v-model="formValidate.attends_user" @input="changeUsers">
                    <el-radio :label="1" class="radio">{{ $t('message.pages.marketing.lottery.create.allUsers') }}</el-radio>
                    <el-radio :label="2">{{ $t('message.pages.marketing.lottery.create.partUsers') }}</el-radio>
                  </el-radio-group>
                </el-form-item>
              </el-col>
              <el-col :span="24" v-if="formValidate.attends_user == 2">
                <el-form-item label="" :prop="formValidate.attends_user == 2 ? 'user_level' : ''">
                  <div class="acea-row row-middle">
                    <el-select
                      multiple
                      v-model="formValidate.user_level"
                      class="content_width"
                      :placeholder="$t('message.pages.marketing.lottery.create.selectUserLevel')"
                    >
                      <el-option
                        v-for="item in userLevelListApi"
                        :value="item.id"
                        :key="item.id"
                        :label="item.name"
                      ></el-option>
                    </el-select>
                  </div>
                </el-form-item>
              </el-col>
              <el-col :span="24" v-if="formValidate.attends_user == 2">
                <el-form-item label="" :prop="formValidate.attends_user == 2 ? 'is_svip' : ''">
                  <div class="acea-row row-middle">
                    <el-select
                      v-model="formValidate.is_svip"
                      clearable
                      :placeholder="$t('message.pages.marketing.lottery.create.selectSvip')"
                      class="content_width"
                    >
                      <el-option
                        v-for="item in templateListTranslated"
                        :value="item.id"
                        :key="item.id"
                        :label="item.name"
                      ></el-option>
                    </el-select>
                  </div>
                </el-form-item>
              </el-col>
              <el-col :span="24" v-if="formValidate.attends_user == 2">
                <el-form-item label="" :prop="formValidate.attends_user == 2 ? 'user_label' : ''">
                  <div class="acea-row row-middle">
                    <div class="labelInput acea-row row-between-wrapper" v-db-click @click="selectLabelShow = true">
                      <div class="">
                        <div v-if="selectDataLabel.length">
                          <el-tag
                            :closable="false"
                            v-for="(item, index) in selectDataLabel"
                            @close="closeLabel(item)"
                            :key="index"
                            class="mr10"
                            >{{ item.label_name }}</el-tag
                          >
                        </div>
                        <span class="span" v-else>{{ $t('message.pages.marketing.lottery.create.selectUserLabel') }}</span>
                      </div>
                      <div class="ivu-icon ivu-icon-ios-arrow-down"></div>
                    </div>
                  </div>
                  <div class="tips-info ml100 grey">{{ $t('message.pages.marketing.lottery.create.labelHint') }}</div>
                </el-form-item>
              </el-col>
              <el-col :span="24" v-if="formValidate.factor == 5">
                <el-form-item
                  :label="$t('message.pages.marketing.lottery.create.lotteryNum')"
                  :prop="formValidate.factor == 5 ? 'lottery_num_term' : ''"
                  label-for="status"
                >
                  <el-radio-group element-id="lottery_num_term" v-model="formValidate.lottery_num_term">
                    <el-radio :label="1" class="radio">{{ $t('message.pages.marketing.lottery.create.dailyN') }}</el-radio>
                    <el-radio :label="2">{{ $t('message.pages.marketing.lottery.create.perUserN') }}</el-radio>
                  </el-radio-group>
                </el-form-item>
              </el-col>
              <el-col :span="24" v-if="formValidate.factor == 5">
                <el-form-item
                  :label="$t('message.pages.marketing.lottery.create.inviteNewUserLottery')"
                  :prop="formValidate.factor == 5 ? 'lottery_num' : ''"
                  label-for="lottery_num"
                >
                  <div class="acea-row row-middle">
                    <div class="mr10 grey"></div>
                    <el-input-number
                      :controls="false"
                      placeholder=""
                      element-id="lottery_num"
                      :min="1"
                      :precision="0"
                      v-model="formValidate.lottery_num"
                      class="content_width"
                    />
                    <div class="ml10 grey">{{ $t('message.pages.marketing.lottery.create.times') }}</div>
                  </div>
                </el-form-item>
              </el-col>
              <el-col :span="24" v-if="formValidate.factor == 5">
                <el-form-item
                  :label="$t('message.pages.marketing.lottery.create.inviteFollowLottery')"
                  :prop="formValidate.factor == 5 ? 'spread_num' : ''"
                  label-for="spread_num"
                >
                  <div class="acea-row row-middle">
                    <div class="mr10 grey"></div>
                    <el-input-number
                      :controls="false"
                      placeholder=""
                      element-id="spread_num"
                      :min="1"
                      :precision="0"
                      v-model="formValidate.spread_num"
                      class="content_width"
                    />
                    <div class="ml10 grey">{{ $t('message.pages.marketing.lottery.create.times') }}</div>
                  </div>
                </el-form-item>
              </el-col>
              <el-col
                :span="24"
                v-if="formValidate.factor == 1 || formValidate.factor == 3 || formValidate.factor == 4"
              >
                <el-form-item
                  :label="formValidate.factor == 1 ? $t('message.pages.marketing.lottery.create.integralCost') : $t('message.pages.marketing.lottery.create.lotteryTimes')"
                  :prop="
                    formValidate.factor == 1 || formValidate.factor == 3 || formValidate.factor == 4 ? 'factor_num' : ''
                  "
                  label-for="factor_num"
                >
                  <div class="acea-row row-middle">
                    <!-- <div class="mr10 grey"></div> -->
                    <el-input-number
                      :controls="false"
                      placeholder=""
                      element-id="factor_num"
                      :min="1"
                      :precision="0"
                      v-model="formValidate.factor_num"
                      class="content_width"
                    >
                    </el-input-number>
                    <!-- <div class="ml10 grey" v-if="formValidate.factor !== 1">次</div> -->
                  </div>
                </el-form-item>
              </el-col>
            </el-row>
            <el-row>
              <el-col :span="24">
                <el-form-item :label="$t('message.pages.marketing.lottery.create.specSelect')" prop="prize">
                  <el-table ref="selection" :data="specsData">
                    <el-table-column min-width="30">
                      <template slot-scope="scope">
                        <div class="drag" @on-drag-drop="onDragDrop">
                          <img class="handle" src="@/assets/images/drag-icon.png" alt="" />
                        </div>
                      </template>
                    </el-table-column>
                    <el-table-column :label="$t('message.pages.marketing.lottery.create.index')" type="index" width="50"> </el-table-column>
                    <el-table-column :label="$t('message.pages.marketing.lottery.create.image')" min-width="80">
                      <template slot-scope="scope">
                        <div
                          class="acea-row scope.row-middle scope.row-center-wrapper"
                          v-db-click
                          @click="modalPicTap('dan', 'goods', scope.$index)"
                        >
                          <div class="pictrue pictrueTab" v-if="scope.row.image">
                            <img v-lazy="scope.row.image" />
                          </div>
                          <div class="upLoad pictrueTab acea-row row-center-wrapper" v-else>
                            <i class="el-icon-picture-outline" style="font-size: 24px"></i>
                          </div>
                        </div>
                      </template>
                    </el-table-column>
                    <el-table-column :label="$t('message.pages.marketing.lottery.create.name')" min-width="80">
                      <template slot-scope="scope">
                        <div>{{ scope.row.name }}</div>
                      </template>
                    </el-table-column>
                    <el-table-column :label="$t('message.pages.marketing.lottery.create.prize')" min-width="80">
                      <template slot-scope="scope">
                        <div>{{ typeName(scope.row.type) }}</div>
                      </template>
                    </el-table-column>
                    <el-table-column :label="$t('message.pages.marketing.lottery.create.prompt')" min-width="80">
                      <template slot-scope="scope">
                        <div>{{ scope.row.prompt }}</div>
                      </template>
                    </el-table-column>
                    <el-table-column :label="$t('message.pages.marketing.lottery.create.quantity')" min-width="80">
                      <template slot-scope="scope">
                        <el-input-number
                          :controls="false"
                          v-model="scope.row.total"
                          :max="9999999999"
                          :min="0"
                          :precision="0"
                          class="priceBox"
                        ></el-input-number>
                      </template>
                    </el-table-column>
                    <el-table-column :label="$t('message.pages.marketing.lottery.create.probability')" min-width="80">
                      <template slot-scope="scope">
                        <el-input-number
                          :controls="false"
                          v-model="scope.row.percent"
                          :max="100"
                          :min="0"
                          :precision="2"
                          class="priceBox"
                        ></el-input-number>
                      </template>
                    </el-table-column>
                    <el-table-column :label="$t('message.pages.marketing.lottery.create.operation')" fixed="right" width="80">
                      <template slot-scope="scope">
                        <a class="submission mr15" v-db-click @click="editGoods(scope.$index)">{{ $t('message.pages.marketing.lottery.create.edit') }}</a>
                      </template>
                    </el-table-column>
                  </el-table>
                  <el-button
                    v-if="specsData.length < 8"
                    type="primary"
                    class="submission mr15 mt20"
                    v-db-click
                    @click="addGoods"
                    >{{ $t('message.pages.marketing.lottery.create.addGoods') }}</el-button
                  >
                </el-form-item>
                <el-form-item>
                  <div class="pl60 grey">
                    {{ $t('message.pages.marketing.lottery.create.prizeHint') }}
                    <el-tooltip effect="light" placement="bottom" width="380">
                      <a>{{ $t('message.pages.marketing.lottery.create.viewExample') }}</a>
                      <div class="api" slot="content">
                        <img src="../../../assets/images/lotteryTest.png" alt="" />
                      </div>
                    </el-tooltip>
                  </div>
                </el-form-item>
              </el-col>
            </el-row>
            <div>
              <el-form-item
                v-if="formValidate.factor != 3 && formValidate.factor != 4"
                :prop="formValidate.factor != 3 && formValidate.factor != 4 ? 'image' : ''"
              >
                <div class="custom-label" slot="label">
                  <div>
                    <div>{{ $t('message.pages.marketing.lottery.create.activityBgImage') }}</div>
                    <div>(750*750)</div>
                  </div>
                  <div>：</div>
                </div>
                <div class="acea-row">
                  <div class="pictrue" v-if="formValidate.image">
                    <img v-lazy="formValidate.image" />
                    <i class="el-icon-circle-close btndel" v-db-click @click="handleRemove()"></i>
                  </div>
                  <div
                    v-else
                    class="upLoad acea-row row-center-wrapper"
                    v-db-click
                    @click="modalPicTap('dan', 'danFrom')"
                  >
                    <i class="el-icon-picture-outline" style="font-size: 24px"></i>
                  </div>
                </div>
              </el-form-item>
              <el-form-item
                v-if="formValidate.factor != 3 && formValidate.factor != 4"
                :label="$t('message.pages.marketing.lottery.create.winnerList')"
                :prop="formValidate.factor != 3 && formValidate.factor != 4 ? 'is_all_record' : ''"
                label-for="is_all_record"
              >
                <el-switch
                  class="defineSwitch"
                  :active-value="1"
                  :inactive-value="0"
                  v-model="formValidate.is_all_record"
                  size="large"
                  :active-text="$t('message.pages.marketing.lottery.create.open')"
                  :inactive-text="$t('message.pages.marketing.lottery.create.close')"
                >
                </el-switch>
              </el-form-item>
              <el-form-item
                v-if="formValidate.factor != 3 && formValidate.factor != 4"
                :label="$t('message.pages.marketing.lottery.create.personalRecord')"
                :prop="formValidate.factor != 3 && formValidate.factor != 4 ? 'is_personal_record' : ''"
                label-for="is_personal_record"
              >
                <el-switch
                  class="defineSwitch"
                  :active-value="1"
                  :inactive-value="0"
                  v-model="formValidate.is_personal_record"
                  size="large"
                  :active-text="$t('message.pages.marketing.lottery.create.open')"
                  :inactive-text="$t('message.pages.marketing.lottery.create.close')"
                >
                </el-switch>
              </el-form-item>
              <el-form-item
                v-if="formValidate.factor != 3 && formValidate.factor != 4"
                :label="$t('message.pages.marketing.lottery.create.activityRule')"
                prop="is_content"
                label-for="is_content"
              >
                <el-switch
                  class="defineSwitch"
                  :active-value="1"
                  :inactive-value="0"
                  v-model="formValidate.is_content"
                  size="large"
                  :active-text="$t('message.pages.marketing.lottery.create.open')"
                  :inactive-text="$t('message.pages.marketing.lottery.create.close')"
                >
                </el-switch>
              </el-form-item>
              <el-form-item
                label=""
                :prop="
                  formValidate.factor != 3 && formValidate.factor != 4 && formValidate.is_content == 1 ? 'content' : ''
                "
                v-show="formValidate.factor != 3 && formValidate.factor != 4 && formValidate.is_content == 1"
              >
                <WangEditor
                  style="width: 90%"
                  :content="formValidate.content"
                  @editorContent="getEditorContent"
                ></WangEditor>
              </el-form-item>
              <el-form-item :label="$t('message.pages.marketing.lottery.create.activityStatus')" prop="status" label-for="status">
                <el-switch
                  class="defineSwitch"
                  :active-value="1"
                  :inactive-value="0"
                  v-model="formValidate.status"
                  size="large"
                  :active-text="$t('message.pages.marketing.lottery.create.open')"
                  :inactive-text="$t('message.pages.marketing.lottery.create.close')"
                >
                </el-switch>
              </el-form-item>
            </div>
            <el-form-item>
              <el-button type="primary" :loading="submitOpen" v-db-click @click="next('formValidate')">{{ $t('message.pages.marketing.lottery.create.submit') }}</el-button>
            </el-form-item>
          </el-form>
        </el-col>
      </el-row>
    </el-card>

    <!-- 上传图片-->
    <el-dialog :visible.sync="modalPic" width="950px" :title="$t('message.pages.marketing.lottery.create.uploadProductImage')" :close-on-click-modal="false">
      <uploadPictures :isChoice="isChoice" @getPic="getPic" v-if="modalPic"></uploadPictures>
    </el-dialog>
    <!-- 上传图片-->
    <el-dialog :visible.sync="addGoodsModel" width="720px" :title="title" :close-on-click-modal="false">
      <addGoods ref="addGoodsForm" v-if="addGoodsModel" @addGoodsData="addGoodsData" :editData="editData"></addGoods>
      <div class="acea-row row-right mt20">
        <el-button v-db-click @click="addGoodsModel = false">{{ $t('message.pages.marketing.lottery.create.cancel') }}</el-button>
        <el-button type="primary" v-db-click @click="submitAddGoods">{{ $t('message.pages.marketing.lottery.create.submit') }}</el-button>
      </div>
    </el-dialog>
    <!-- 用户标签 -->
    <el-dialog
      :visible.sync="selectLabelShow"
      scrollable
      :title="$t('message.pages.marketing.lottery.create.selectUserLabelTitle')"
      :closable="false"
      width="540px"
      :footer-hide="true"
      :mask-closable="false"
    >
      <userLabel
        v-if="selectLabelShow"
        :uid="0"
        ref="userLabel"
        :only_get="true"
        :selectDataLabel="selectDataLabel"
        @activeData="activeSelectData"
        @close="labelClose"
      ></userLabel>
    </el-dialog>
  </div>
</template>

<script>
import { mapState } from 'vuex';
import goodsList from '@/components/goodsList/index';
import uploadPictures from '@/components/uploadPictures';
import userLabel from '@/components/userLabel';
import addGoods from './addGoods';
import { lotteryNewDetailApi, lotteryDetailApi, lotteryCreateApi, lotteryEditApi } from '@/api/lottery'; //详情 创建 编辑
import { labelListApi } from '@/api/product';
import { levelListApi } from '@/api/user';
import WangEditor from '@/components/wangEditor/index.vue';

import { formatDate } from '@/utils/validate';
import { formatRichText } from '@/utils/editorImg';
import Sortable from 'sortablejs';

export default {
  name: 'lotteryCreate',
  components: {
    goodsList,
    uploadPictures,
    addGoods,
    WangEditor,
    userLabel,
  },
  data() {
    return {
      selectDataLabel: [],
      selectLabelShow: false,
      content: '',
      title: '',
      loading: false,
      userLabelList: [], //用户标签列表
      userLevelListApi: [], //用户等级列表
      submitOpen: false,
      spinShow: false,
      addGoodsModel: false,
      editData: {},
      myConfig: {
        autoHeightEnabled: false, // 编辑器不自动被内容撑高
        initialFrameHeight: 500, // 初始容器高度
        initialFrameWidth: '100%', // 初始容器宽度
        UEDITOR_HOME_URL: '/UEditor/',
        serverUrl: '',
      },
      isChoice: '',
      current: 0,
      modalPic: false,
      modal_loading: false,
      images: [],
      specsData: [
        {
          type: 1, //类型 1：未中奖 2：积分  3:余额  4：红包 5:优惠券 6：站内商品
          name: '', //活动名称
          num: 10, //奖品数量
          image: '', //奖品图片
          chance: 1, //中奖权重
          total: 0, //奖品数量
          percent: 0, //中奖概率
          min_try_num: 0, //抽奖次数尝试
          prompt: '', //提示语
        },
        {
          type: 1, //类型 1：未中奖 2：积分  3:余额  4：红包 5:优惠券 6：站内商品
          name: '', //活动名称
          num: 10, //奖品数量
          image: '', //奖品图片
          chance: 1, //中奖权重
          total: 0, //奖品数量
          percent: 0, //中奖概率
          min_try_num: 0, //抽奖次数尝试
          prompt: '', //提示语
        },
        {
          type: 1, //类型 1：未中奖 2：积分  3:余额  4：红包 5:优惠券 6：站内商品
          name: '', //活动名称
          num: 10, //奖品数量
          image: '', //奖品图片
          chance: 1, //中奖权重
          total: 0, //奖品数量
          percent: 0, //中奖概率
          min_try_num: 0, //抽奖次数尝试
          prompt: '', //提示语
        },
        {
          type: 1, //类型 1：未中奖 2：积分  3:余额  4：红包 5:优惠券 6：站内商品
          name: '', //活动名称
          num: 10, //奖品数量
          image: '', //奖品图片
          chance: 1, //中奖权重
          total: 0, //奖品数量
          percent: 0, //中奖概率
          min_try_num: 0, //抽奖次数尝试
          prompt: '', //提示语
        },
        {
          type: 1, //类型 1：未中奖 2：积分  3:余额  4：红包 5:优惠券 6：站内商品
          name: '', //活动名称
          num: 10, //奖品数量
          image: '', //奖品图片
          chance: 1, //中奖权重
          total: 0, //奖品数量
          percent: 0, //中奖概率
          min_try_num: 0, //抽奖次数尝试
          prompt: '', //提示语
        },
        {
          type: 1, //类型 1：未中奖 2：积分  3:余额  4：红包 5:优惠券 6：站内商品
          name: '', //活动名称
          num: 10, //奖品数量
          image: '', //奖品图片
          chance: 1, //中奖权重
          total: 0, //奖品数量
          percent: 0, //中奖概率
          min_try_num: 0, //抽奖次数尝试
          prompt: '', //提示语
        },
        {
          type: 1, //类型 1：未中奖 2：积分  3:余额  4：红包 5:优惠券 6：站内商品
          name: '', //活动名称
          num: 10, //奖品数量
          image: '', //奖品图片
          chance: 1, //中奖权重
          total: 0, //奖品数量
          percent: 0, //中奖概率
          min_try_num: 0, //抽奖次数尝试
          prompt: '', //提示语
        },
        {
          type: 1, //类型 1：未中奖 2：积分  3:余额  4：红包 5:优惠券 6：站内商品
          name: '', //活动名称
          num: 10, //奖品数量
          image: '', //奖品图片
          chance: 1, //中奖权重
          total: 0, //奖品数量
          percent: 0, //中奖概率
          min_try_num: 0, //抽奖次数尝试
          prompt: '', //提示语
        },
      ],
      formValidate: {
        images: [],
        name: '', //活动名称
        desc: '', //活动描述
        image: '', //活动背景图
        factor: '1', //抽奖类型：1:积分 2:余额 3：下单支付成功 4:订单评价',5:关注
        factor_num: 1, //获取一次抽奖的条件数量
        attends_user: 1, //参与用户1：所有  2：部分
        user_level: [], //参与用户等级
        user_label: [], //参与用户标签
        is_svip: '', //参与用户是否付费会员
        prize_num: 0, //奖品数量
        period: [], //活动时间
        prize: [], //奖品数组
        lottery_num_term: 1, //抽奖次数限制：1：每天2：每人
        lottery_num: 1, //抽奖次数
        spread_num: 1, //关注推广获取抽奖次数
        is_all_record: 0, //中奖纪录展示
        is_personal_record: 0, //个人中奖纪录展示
        is_content: 0, //活动规格是否展示
        content: '', //富文本内容
        status: 0, //状态
      },
      ruleValidate: {},
      currentid: '',
      picTit: '',
      tableIndex: 0,
      copy: 0,
      editIndex: null,
      id: '',
      copy: 0,
      lottery_id: 0,
    };
  },
  computed: {
    ...mapState('admin/layout', ['isMobile']),
    labelWidth() {
      return this.isMobile ? undefined : '120px';
    },
    labelPosition() {
      return this.isMobile ? 'top' : 'right';
    },
    tabs() {
      return [
        { name: this.$t('message.pages.marketing.lottery.create.integralDraw'), type: '1' },
        { name: this.$t('message.pages.marketing.lottery.create.orderPay'), type: '3' },
        { name: this.$t('message.pages.marketing.lottery.create.orderReview'), type: '4' },
      ];
    },
    templateListTranslated() {
      return [
        { id: -1, name: this.$t('message.pages.marketing.lottery.create.noLimitMember') },
        { id: 0, name: this.$t('message.pages.marketing.lottery.create.nonPaidMember') },
        { id: 1, name: this.$t('message.pages.marketing.lottery.create.paidMember') },
      ];
    },
  },
  mounted() {
    this.setRuleValidate();
    this.labelListApi();
    this.levelListApi();
    if (this.$route.query.type) {
      this.formValidate.factor = this.$route.query.type;
    }
    if (this.$route.query.lottery_id) {
      this.lottery_id = this.$route.query.lottery_id;
      this.getInfo();
    }
    this.$nextTick((e) => {
      this.setSort();
    });
  },
  methods: {
    typeName(type) {
      const p = 'message.pages.marketing.lottery.create.';
      if (type == 1) return this.$t(p + 'typeNotWon');
      if (type == 2) return this.$t(p + 'typeIntegral');
      if (type == 3) return this.$t(p + 'typeBalance');
      if (type == 4) return this.$t(p + 'typeRedPacket');
      if (type == 5) return this.$t(p + 'typeCoupon');
      if (type == 6) return this.$t(p + 'typeProduct');
      return '';
    },
    setRuleValidate() {
      const t = this.$t.bind(this);
      const p = 'message.pages.marketing.lottery.create.';
      this.ruleValidate = {
        name: [{ required: true, message: t(p + 'msgInputActivityName'), trigger: 'blur' }],
        factor: [{ required: true, type: 'number', message: t(p + 'msgSelectActivityType'), trigger: 'change' }],
        attends_user: [{ required: true, type: 'number', message: t(p + 'msgSelectAttendsUser'), trigger: 'change' }],
        factor_num: [{ required: true, type: 'number', message: t(p + 'msgInputLotteryTimes'), trigger: 'blur' }],
        prize: [
          { required: true, type: 'array', message: t(p + 'msgAdd8Prizes'), trigger: 'change' },
          { type: 'array', min: 8, message: t(p + 'msgAdd8Prizes'), trigger: 'change' },
        ],
        lottery_num: [{ required: true, type: 'number', message: t(p + 'msgInputInviteLottery'), trigger: 'blur' }],
        spread_num: [{ required: true, type: 'number', message: t(p + 'msgInputFollowLottery'), trigger: 'blur' }],
        image: [{ required: true, message: t(p + 'msgUploadBgImage'), trigger: 'change' }],
        content: [{ required: true, message: t(p + 'msgFillActivityRule'), trigger: 'blur' }],
      };
    },
    submitAddGoods() {
      this.$refs.addGoodsForm.handleSubmit('formValidate');
    },
    changeUsers(e) {
      if (e == 1) {
        this.formValidate.user_level = []; //参与用户等级
        this.formValidate.user_label = []; //参与用户标签
        this.formValidate.is_svip = '-1'; //参与用户是否付费会员
        this.selectDataLabel = []; //参与用户是否付费会员
      }
    },
    // 标签弹窗关闭
    labelClose() {
      this.selectLabelShow = false;
    },
    activeSelectData(data) {
      this.selectLabelShow = false;
      this.selectDataLabel = data;
    },
    onClickTab(e) {
      if (this.lottery_id) this.getInfo();
    },
    getEditorContent(data) {
      this.content = data;
    },
    //用户标签列表
    labelListApi() {
      labelListApi().then((res) => {
        this.userLabelList = res.data.list;
      });
    },
    //用户等级列表
    levelListApi() {
      levelListApi().then((res) => {
        this.userLevelListApi = res.data.list;
      });
    },
    // 具体日期
    onchangeTime(e) {
      this.$set(this.formValidate, 'period', e);
    },
    // 详情
    getInfo(e) {
      this.spinShow = true;
      lotteryDetailApi(this.lottery_id)
        .then((res) => {
          if (res.status == 200 && !Array.isArray(res.data)) {
            this.formValidate = res.data;
            this.formValidate.user_level = res.data.user_level || [];
            this.selectDataLabel = res.data.user_label || [];
            this.formValidate.is_svip = res.data.is_svip;
            this.content = res.data.is_content ? res.data.content : '';
            this.formValidate.factor = res.data.factor.toString();
            this.$set(this.formValidate, 'period', [
              this.formatDate(res.data.start_time) || '',
              this.formatDate(res.data.end_time) || '',
            ]);
            this.specsData = res.data.prize;
            this.getProbability();
          } else {
            this.formValidate = {
              images: [],
              name: '', //活动名称
              desc: '', //活动描述
              image: '', //活动背景图
              factor: e.toString(), //抽奖类型：1:积分 2:余额 3：下单支付成功 4:订单评价',5:关注
              factor_num: 1, //获取一次抽奖的条件数量
              attends_user: 1, //参与用户1：所有  2：部分
              user_level: [], //参与用户等级
              user_label: [], //参与用户标签
              is_svip: '-1', //参与用户是否付费会员
              prize_num: 0, //奖品数量
              period: [], //活动时间
              prize: [], //奖品数组
              lottery_num_term: 1, //抽奖次数限制：1：每天2：每人
              lottery_num: 1, //抽奖次数
              spread_num: 1, //关注推广获取抽奖次数
              is_all_record: 0, //中奖纪录展示
              is_personal_record: 0, //个人中奖纪录展示
              is_content: 0, //活动规格是否展示
              content: '', //富文本内容
              status: 0, //状态
            };
            this.specsData = [
              {
                type: 1, //类型 1：未中奖 2：积分  3:余额  4：红包 5:优惠券 6：站内商品
                name: '', //活动名称
                num: 10, //奖品数量
                image: '', //奖品图片
                chance: 1, //中奖权重
                total: 0, //奖品数量
                percent: 0, //中奖概率
                min_try_num: 0, //抽奖次数尝试
                prompt: '', //提示语
              },
              {
                type: 1, //类型 1：未中奖 2：积分  3:余额  4：红包 5:优惠券 6：站内商品
                name: '', //活动名称
                num: 10, //奖品数量
                image: '', //奖品图片
                chance: 1, //中奖权重
                total: 0, //奖品数量
                percent: 0, //中奖概率
                min_try_num: 0, //抽奖次数尝试
                prompt: '', //提示语
              },
              {
                type: 1, //类型 1：未中奖 2：积分  3:余额  4：红包 5:优惠券 6：站内商品
                name: '', //活动名称
                num: 10, //奖品数量
                image: '', //奖品图片
                chance: 1, //中奖权重
                total: 0, //奖品数量
                percent: 0, //中奖概率
                min_try_num: 0, //抽奖次数尝试
                prompt: '', //提示语
              },
              {
                type: 1, //类型 1：未中奖 2：积分  3:余额  4：红包 5:优惠券 6：站内商品
                name: '', //活动名称
                num: 10, //奖品数量
                image: '', //奖品图片
                chance: 1, //中奖权重
                total: 0, //奖品数量
                percent: 0, //中奖概率
                min_try_num: 0, //抽奖次数尝试
                prompt: '', //提示语
              },
              {
                type: 1, //类型 1：未中奖 2：积分  3:余额  4：红包 5:优惠券 6：站内商品
                name: '', //活动名称
                num: 10, //奖品数量
                image: '', //奖品图片
                chance: 1, //中奖权重
                total: 0, //奖品数量
                percent: 0, //中奖概率
                min_try_num: 0, //抽奖次数尝试
                prompt: '', //提示语
              },
              {
                type: 1, //类型 1：未中奖 2：积分  3:余额  4：红包 5:优惠券 6：站内商品
                name: '', //活动名称
                num: 10, //奖品数量
                image: '', //奖品图片
                chance: 1, //中奖权重
                total: 0, //奖品数量
                percent: 0, //中奖概率
                min_try_num: 0, //抽奖次数尝试
                prompt: '', //提示语
              },
              {
                type: 1, //类型 1：未中奖 2：积分  3:余额  4：红包 5:优惠券 6：站内商品
                name: '', //活动名称
                num: 10, //奖品数量
                image: '', //奖品图片
                chance: 1, //中奖权重
                total: 0, //奖品数量
                percent: 0, //中奖概率
                min_try_num: 0, //抽奖次数尝试
                prompt: '', //提示语
              },
              {
                type: 1, //类型 1：未中奖 2：积分  3:余额  4：红包 5:优惠券 6：站内商品
                name: '', //活动名称
                num: 10, //奖品数量
                image: '', //奖品图片
                chance: 1, //中奖权重
                total: 0, //奖品数量
                percent: 0, //中奖概率
                min_try_num: 0, //抽奖次数尝试
                prompt: '', //提示语
              },
            ];
          }
          this.$nextTick((e) => {
            this.spinShow = false;
          });
        })
        .catch((err) => {});
    },
    // 下一步
    next(name) {
      this.formValidate.prize = this.specsData;
      if (this.formValidate.is_content) {
        this.formValidate.content = formatRichText(this.content);
      }
      if (this.formValidate.attends_user == 2) {
        if (this.selectDataLabel.length) {
          let activeIds = [];
          this.selectDataLabel.forEach((item) => {
            activeIds.push(item.id);
          });
          this.formValidate.user_label = activeIds;
        }
      }
      if (this.submitOpen) return false;
      this.$refs[name].validate((valid) => {
        if (valid) {
          this.submitOpen = true;
          if (this.formValidate.id && !this.copy) {
            lotteryEditApi(this.formValidate.id, this.formValidate)
              .then(async (res) => {
                this.$message.success(res.msg);
                this.submitOpen = false;
                setTimeout(() => {
                  this.$router.push({
                    path: '/admin/marketing/lottery/list',
                  });
                }, 500);
              })
              .catch((res) => {
                this.submitOpen = false;
                this.$message.error(res.msg);
              });
          } else {
            lotteryCreateApi(this.formValidate)
              .then(async (res) => {
                this.submitOpen = false;
                this.$message.success(res.msg);
                setTimeout(() => {
                  this.$router.push({
                    path: '/admin/marketing/lottery/list',
                  });
                }, 500);
              })
              .catch((res) => {
                this.submitOpen = false;
                this.$message.error(res.msg);
              });
          }
        } else {
          return false;
        }
      });
    },
    // 上一步
    step() {
      this.current--;
    },
    // 点击商品图
    modalPicTap(tit, picTit, index) {
      this.modalPic = true;
      this.isChoice = tit === 'dan' ? this.$t('message.pages.marketing.lottery.create.singleSelect') : this.$t('message.pages.marketing.lottery.create.multiSelect');
      this.picTit = picTit || '';
      this.tableIndex = index;
    },
    // 获取单张图片信息
    getPic(pc) {
      switch (this.picTit) {
        case 'danFrom':
          this.formValidate.image = pc.att_dir;
          break;
        default:
          this.specsData[this.tableIndex].image = pc.att_dir;
      }
      this.modalPic = false;
    },
    handleRemove() {
      this.formValidate.image = '';
    },
    // 表单验证
    validate(prop, status, error) {
      if (status === false) {
        this.$message.error(error);
        return false;
      } else {
        return true;
      }
    },
    //新增商品
    addGoods() {
      this.addGoodsModel = true;
      this.title = this.$t('message.pages.marketing.lottery.create.addGoodsTitle');
      this.editData = {};
    },
    //编辑商品
    editGoods(index) {
      this.addGoodsModel = true;
      this.title = this.$t('message.pages.marketing.lottery.create.editPrize');
      this.editData = this.specsData[index];
      this.editIndex = index;
    },
    //删除商品
    deleteGoods(index) {
      this.specsData.splice(index, 1);
    },
    //获取数组中某个字段之和
    sumArr(arr, name) {
      let arrData = [];
      for (let i = 0; i < arr.length; i++) {
        arrData.push(arr[i][name]);
      }
      return eval(arrData.join('+'));
    },
    addGoodsData(data) {
      this.editIndex != null
        ? this.$set(this.specsData, [this.editIndex], data)
        : this.specsData.length < 8
        ? this.specsData.push(data)
        : this.$message.warning(this.$t('message.pages.marketing.lottery.create.max8Prize'));
      this.getProbability();
      this.addGoodsModel = false;
      this.editIndex = null;
    },
    changeChance(e, index) {
      console.log(e, index);
      let value = e.target.value;
      this.$set(this.specsData[index], 'percent', value);
    },
    changeTotal(data, index) {
      this.$set(this.specsData[index], 'total', data);
    },
    //获取商品中奖概率
    getProbability() {
      let sum = 0;
      sum = this.sumArr(this.specsData, 'chance');
      for (let j = 0; j < this.specsData.length; j++) {
        if (sum == 0) {
          this.$set(this.specsData[j], 'probability', '0%');
        } else {
          this.$set(this.specsData[j], 'probability', ((this.specsData[j].chance / sum) * 100).toFixed(2) + '%');
        }
      }
    },
    //修改排序
    onDragDrop(a, b) {
      this.specsData.splice(b, 1, ...this.specsData.splice(a, 1, this.specsData[b]));
    },
    setSort() {
      // ref一定跟table上面的ref一致
      const el = this.$refs.selection.$el.querySelectorAll('.el-table__body-wrapper > table > tbody')[0];
      this.sortable = Sortable.create(el, {
        ghostClass: 'sortable-ghost',
        handle: '.handle',
        setData: function (dataTransfer) {
          dataTransfer.setData('Text', '');
        },
        // 监听拖拽事件结束时触发
        onEnd: (evt) => {
          this.elChangeExForArray(evt.oldIndex, evt.newIndex, this.specsData);
        },
      });
    },
    elChangeExForArray(index1, index2, array, init) {
      const arr = array;
      const temp = array[index1];
      const tempt = array[index2];
      if (init) {
        arr[index2] = tempt;
        arr[index1] = temp;
      } else {
        arr[index1] = tempt;
        arr[index2] = temp;
      }
      this.specsData = [];
      this.$nextTick((e) => {
        this.specsData = arr;
      });
    },
    //时间格式转换
    formatDate(time) {
      if (time) {
        let date = new Date(time * 1000);
        return formatDate(date, 'yyyy-MM-dd hh:mm');
      } else {
        return '';
      }
    },
  },
};
</script>

<style lang="scss" scoped>
.content_width {
  width: 460px;
}
::v-deep .el-tabs__item {
  height: 54px !important;
  line-height: 54px !important;
}
.custom-label {
  display: inline-flex;
  line-height: 1.5;
}
.grey {
  color: #999;
}
.maxW ::v-deep .ivu-select-dropdown {
  max-width: 600px;
}
.ivu-table-wrapper {
  border-left: 1px solid #dcdee2;
  border-top: 1px solid #dcdee2;
}
.tabBox_img {
  width: 50px;
  height: 50px;
}
.tabBox_img img {
  width: 100%;
  height: 100%;
}
.priceBox {
  width: 100%;
}
.form {
  .picBox {
    display: inline-block;
    cursor: pointer;
  }
  .pictrue {
    width: 58px;
    height: 58px;
    border: 1px dotted rgba(0, 0, 0, 0.1);
    margin-right: 0px;
    display: inline-block;
    position: relative;
    cursor: pointer;

    img {
      width: 100%;
      height: 100%;
    }
    .btndel {
      position: absolute;
      z-index: 9;
      font-size: 20px;
      left: 46px;
      top: -4px;
    }
  }
  .upLoad {
    width: 58px;
    height: 58px;
    line-height: 58px;
    border: 1px dotted rgba(0, 0, 0, 0.1);
    border-radius: 4px;
    background: rgba(0, 0, 0, 0.02);
    cursor: pointer;
  }
}
.labelInput {
  border: 1px solid #dcdee2;
  padding: 0 15px;
  width: 460px;
  border-radius: 5px;
  min-height: 30px;
  cursor: pointer;
  .span {
    font-size: 12px;
    color: #c5c8ce;
  }
  .ivu-icon-ios-arrow-down {
    font-size: 14px;
    color: #808695;
  }
}
</style>
