<template>
  <div class="article-manager">
    <el-card :bordered="false" shadow="never" class="ivu-mt" :body-style="{ padding: 0 }">
      <div class="padding-add">
        <el-form ref="artFrom" :model="artFrom" label-width="auto" label-position="right" inline @submit.native.prevent>
          <div class="acea-row search-form">
            <div class="search-form-box">
              <el-form-item label="Tìm kiếm sản phẩm：" label-for="store_name">
                <el-input
                  clearable
                  placeholder="Vui lòng nhập tên sản phẩm/từ khóa/ID"
                  v-model="artFrom.store_name"
                  class="form_content_width"
                />
              </el-form-item>
              <el-form-item label="Loại sản phẩm：">
                <el-select v-model="artFrom.virtual_type" clearable placeholder="Tất cả" class="form_content_width">
                  <el-option label="Tất cả" value="" />
                  <el-option label="Hàng thông thường" value="0" />
                  <el-option label="Sản phẩm thẻ" value="1" />
                  <el-option label="Sản phẩm phiếu giảm giá" value="2" />
                  <el-option label="Hàng ảo" value="3" />
                </el-select>
              </el-form-item>
              <el-form-item label="Danh mục sản phẩm：" label-for="pid">
                <el-cascader
                  v-model="artFrom.cate_id"
                  size="small"
                  :options="treeSelect"
                  :props="{ multiple: false, emitPath: false, checkStrictly: true }"
                  clearable
                  class="form_content_width"
                ></el-cascader>
              </el-form-item>
              <el-form-item label="Phương thức giao hàng：">
                <el-select v-model="artFrom.logistics" clearable placeholder="Tất cả" class="form_content_width">
                  <el-option label="Tất cả" value="" />
                  <el-option label="Chuyển phát nhanh" value="1" />
                  <el-option label="Nhận tại cửa hàng" value="2" />
                </el-select>
              </el-form-item>
              <template v-if="collapse">
                <el-form-item label="Nhãn sản phẩm：" label-for="store_name">
                  <div class="labelInput acea-row row-between-wrapper form_content_width" @click="openStoreLabel">
                    <div style="width: 90%">
                      <div v-if="storeLabelList.length">
                        <el-tag
                          class="mr5"
                          closable
                          v-for="(item, index) in storeLabelList"
                          :key="index"
                          @close="closeStoreLabel(item)"
                          >{{ item.label_name }}</el-tag
                        >
                      </div>
                      <span class="span" v-else>Chọn thẻ sản phẩm</span>
                    </div>
                    <div class="iconfont iconxiayi"></div>
                  </div>
                </el-form-item>
                <el-form-item label="Thuộc tính sản phẩm：">
                  <el-select v-model="artFrom.spec_type" clearable placeholder="Tất cả" class="form_content_width">
                    <el-option label="Tất cả" value="" />
                    <el-option label="Một thuộc tính" value="0" />
                    <el-option label="Nhiều thuộc tính" value="1" />
                  </el-select>
                </el-form-item>
                <el-form-item label="Chỉ thành viên：">
                  <el-select v-model="artFrom.vip_product" clearable placeholder="Tất cả" class="form_content_width">
                    <el-option label="Tất cả" value="" />
                    <el-option label="Không" value="0" />
                    <el-option label="Có" value="1" />
                  </el-select>
                </el-form-item>
                <el-form-item label="Nó có phải là một món quà?：">
                  <el-select v-model="artFrom.is_gift" clearable placeholder="Tất cả" class="form_content_width">
                    <el-option label="Tất cả" value="" />
                    <el-option label="Không" value="0" />
                    <el-option label="Có" value="1" />
                  </el-select>
                </el-form-item>

                <el-form-item label="Thêm thời gian：">
                  <el-date-picker
                    class="form_range_content_width date-range-vi"
                    clearable
                    v-model="timeVal"
                    type="daterange"
                    :editable="false"
                    @change="onchangeTime"
                    format="dd/MM/yyyy"
                    value-format="yyyy-MM-dd"
                    start-placeholder="Ngày bắt đầu"
                    end-placeholder="Ngày kết thúc"
                    :picker-options="pickerOptions"
                  ></el-date-picker>
                </el-form-item>
                <el-form-item label="Trong kho：" label-for="store_name">
                  <el-input
                    clearable
                    placeholder="Tối thiểu"
                    v-model="artFrom.stock_s[0]"
                    class="form_range_content_width"
                  />
                  ~
                  <el-input
                    clearable
                    placeholder="Tối đa"
                    v-model="artFrom.stock_s[1]"
                    class="form_range_content_width"
                  />
                </el-form-item>
                <el-form-item label="Giá：" label-for="store_name">
                  <el-input
                    clearable
                    placeholder="Tối thiểu"
                    v-model="artFrom.price_s[0]"
                    class="form_range_content_width"
                  />
                  ~
                  <el-input
                    clearable
                    placeholder="Tối đa"
                    v-model="artFrom.price_s[1]"
                    class="form_range_content_width"
                  />
                </el-form-item>
                <el-form-item label="Doanh số bán hàng：" label-for="store_name">
                  <el-input
                    clearable
                    placeholder="Tối thiểu"
                    v-model="artFrom.sales_s[0]"
                    class="form_range_content_width"
                  />
                  ~
                  <el-input
                    clearable
                    placeholder="Tối đa"
                    v-model="artFrom.sales_s[1]"
                    class="form_range_content_width"
                  />
                </el-form-item>
              </template>
            </div>
            <div class="search-form-sub">
              <el-button type="primary" v-db-click @click="userSearchs">Tìm kiếm</el-button>
              <el-button class="ResetSearch" v-db-click @click="reset">Đặt lại</el-button>
              <a class="ivu-ml-8 font12 ml10" v-db-click @click="collapse = !collapse">
                <template v-if="!collapse"> Mở rộng <i class="el-icon-arrow-down" /> </template>
                <template v-else> Đóng <i class="el-icon-arrow-up" /> </template>
              </a>
            </div>
          </div>
        </el-form>
      </div>
    </el-card>
    <el-card :bordered="false" shadow="never" class="ivu-mt mt16" :body-style="{ padding: '0 20px 20px' }">
      <el-tabs v-model="artFrom.type" @tab-click="onClickTab">
        <el-tab-pane
          :label="item.name + '(' + item.count + ')'"
          :name="item.type.toString()"
          v-for="(item, index) in headeNum"
          :key="index"
        />
      </el-tabs>
      <div class="Button">
        <router-link v-auth="['product-product-save']" :to="$routeProStr + '/product/add_product'"
          ><el-button type="primary" class="mr14">Thêm sản phẩm</el-button></router-link
        >
        <el-button v-auth="['product-crawl-save']" type="success" class="mr14" v-db-click @click="onCopy"
          >Sản phẩm yêu thích</el-button
        >
        <el-dropdown class="bnt mr14" @command="batchSelect">
          <el-button>Sửa hàng loạt<i class="el-icon-arrow-down el-icon--right"></i></el-button>
          <el-dropdown-menu slot="dropdown">
            <el-dropdown-item :command="1">Danh mục sản phẩm</el-dropdown-item>
            <el-dropdown-item :command="2">Cấu hình vận chuyển</el-dropdown-item>
            <el-dropdown-item :command="3">Mua và nhận điểm</el-dropdown-item>
            <el-dropdown-item :command="4">Mua và nhận phiếu giảm giá</el-dropdown-item>
            <el-dropdown-item :command="5">Thẻ khách hàng được liên kết</el-dropdown-item>
            <el-dropdown-item :command="6">Hoạt động được đề xuất</el-dropdown-item>
            <el-dropdown-item v-auth="['product-product-product_show']" v-if="artFrom.type === '1'" :command="7"
              >Xóa hàng loạt</el-dropdown-item
            >
            <el-dropdown-item v-auth="['product-product-product_show']" v-if="artFrom.type === '2'" :command="8"
              >Danh sách hàng loạt</el-dropdown-item
            >
            <el-dropdown-item v-auth="['product-product-product_show']" :command="9">Đặt nhãn sản phẩm</el-dropdown-item>
            <el-dropdown-item v-auth="['product-product-product_show']" v-if="artFrom.type !== '6'" :command="11"
              >Di chuyển vào thùng rác</el-dropdown-item
            >
            <el-dropdown-item v-auth="['product-product-product_show']" v-if="artFrom.type == '6'" :command="12"
              >Khôi phục sản phẩm</el-dropdown-item
            >
          </el-dropdown-menu>
        </el-dropdown>
        <el-dropdown class="bnt mr14" @command="goodsMove">
          <el-button>Di chuyển sản phẩm<i class="el-icon-arrow-down el-icon--right"></i></el-button>
          <el-dropdown-menu slot="dropdown">
            <el-dropdown-item :command="1">Nhập khẩu sản phẩm</el-dropdown-item>
            <el-dropdown-item :command="2">Xuất file sản phẩm</el-dropdown-item>
          </el-dropdown-menu>
        </el-dropdown>
        <el-button v-auth="['export-storeProduct']" class="export" v-db-click @click="onExports(0)">Xuất dữ liệu</el-button>
      </div>
      <el-table
        ref="table"
        :data="tableList"
        class="ivu-mt mt14"
        v-loading="loading"
        highlight-current-row
        :row-key="getRowKey"
        @selection-change="handleSelectRow"
        empty-text="Chưa có dữ liệu"
      >
        <el-table-column type="expand" width="50" v-if="['1', '2'].includes(artFrom.type)">
          <template slot-scope="scope">
            <expandRow :row="scope.row"></expandRow>
          </template>
        </el-table-column>
        <el-table-column type="selection" width="60" :reserve-selection="true"> </el-table-column>
        <el-table-column label="ID sản phẩm" min-width="90">
          <template slot-scope="scope">
            <span>{{ scope.row.id }}</span>
          </template>
        </el-table-column>
        <el-table-column label="Hình ảnh sản phẩm" min-width="90">
          <template slot-scope="scope">
            <div class="tabBox_img" v-viewer>
              <img v-lazy="scope.row.image" />
            </div>
          </template>
        </el-table-column>
        <el-table-column label="Tên sản phẩm" min-width="280" show-overflow-tooltip>
          <template slot-scope="scope">
            <span>{{ scope.row.store_name }}</span>
          </template>
        </el-table-column>
        <el-table-column label="Tham gia các hoạt động" min-width="120">
          <template slot-scope="scope">
            <el-tag
              class="mb5 cup"
              v-if="scope.row.activityExist.bargain"
              type=""
              @click="activityDetail(scope.row, 0)"
              effect="dark"
            >
              Mặc cả
            </el-tag>
            <el-tag
              class="mb5 cup"
              v-if="scope.row.activityExist.combination"
              type="success"
              @click="activityDetail(scope.row, 1)"
              effect="dark"
            >
              Chia sẻ nhóm
            </el-tag>
            <el-tag
              class="mb5 cup"
              v-if="scope.row.activityExist.seckill"
              type="warning"
              @click="activityDetail(scope.row, 2)"
              effect="dark"
            >
              Bán chớp nhoáng
            </el-tag>
          </template>
        </el-table-column>
        <el-table-column label="Loại sản phẩm" min-width="100">
          <template slot-scope="scope">
            <span>{{ scope.row.product_type }}</span>
          </template>
        </el-table-column>
        <el-table-column label="Giá bán sản phẩm" min-width="100">
          <template slot-scope="scope">
            <span>{{ scope.row.price }}</span>
          </template>
        </el-table-column>
        <el-table-column label="Doanh số bán hàng" min-width="100">
          <template slot-scope="scope">
            <span>{{ scope.row.sales }}</span>
          </template>
        </el-table-column>
        <el-table-column label="Trong kho" min-width="100">
          <template slot-scope="scope">
            <span>{{ scope.row.stock }}</span>
          </template>
        </el-table-column>
        <el-table-column label="Loại" min-width="100">
          <template slot-scope="scope">
            <span>{{ scope.row.sort }}</span>
          </template>
        </el-table-column>
        <el-table-column label="Trạng thái" min-width="100">
          <template slot-scope="scope">
            <el-switch
              class="defineSwitch"
              :active-value="1"
              :inactive-value="0"
              v-model="scope.row.is_show"
              :value="scope.row.is_show"
              :disabled="scope.row.stop_status ? true : false"
              @change="changeSwitch(scope.row)"
              size="large"
              active-text="Trên kệ"
              inactive-text="Đã xóa khỏi kệ"
            >
            </el-switch>
          </template>
        </el-table-column>
        <el-table-column label="Thao tác" fixed="right" minWidth="100">
          <template slot-scope="scope">
            <!-- <a v-db-click @click="look(scope.row)">Kiểm tra</a>
            <el-divider direction="vertical"></el-divider> -->
            <a v-db-click @click="edit(scope.row)">Chỉnh sửa</a>
            <el-divider direction="vertical"></el-divider>
            <el-dropdown size="small">
              <span class="el-dropdown-link">Thêm<i class="el-icon-arrow-down el-icon--right"></i> </span>
              <el-dropdown-menu slot="dropdown">
                <el-dropdown-item>
                  <router-link :to="{ path: $routeProStr + '/product/product_reply/' + scope.row.id }"
                    ><a>Xem bình luận</a></router-link
                  >
                </el-dropdown-item>
                <el-dropdown-item v-db-click @click.native="openModal(scope.row, 'vipPriceSet')"
                  >Quản lý giá thành viên</el-dropdown-item
                >
                <el-dropdown-item v-db-click @click.native="openModal(scope.row, 'brokerageSet')"
                  >Quản lý hoa hồng</el-dropdown-item
                >
                <el-dropdown-item
                  v-if="artFrom.type === '6'"
                  v-db-click
                  @click.native="del(scope.row, 'Khôi phục sản phẩm', scope.$index)"
                  >Khôi phục sản phẩm</el-dropdown-item
                >
                <el-dropdown-item
                  v-if="artFrom.type === '6'"
                  v-db-click
                  @click.native="fullDel(scope.row, 'Xóa hoàn toàn', scope.$index)"
                  >Xóa hoàn toàn</el-dropdown-item
                >
                <el-dropdown-item v-else v-db-click @click.native="del(scope.row, 'Di chuyển vào thùng rác', scope.$index)"
                  >Di chuyển vào thùng rác</el-dropdown-item
                >
              </el-dropdown-menu>
            </el-dropdown>
          </template>
        </el-table-column>
      </el-table>
      <div class="acea-row row-right page">
        <pagination
          v-if="total"
          :total="total"
          :page.sync="artFrom.page"
          :limit.sync="artFrom.limit"
          @pagination="getDataList"
        />
      </div>
      <attribute :attrTemplate="attrTemplate" v-on:changeTemplate="changeTemplate"></attribute>
    </el-card>
    <!-- Tạo biểu mẫu JD của taobao-->
    <el-dialog
      :visible.sync="modals"
      class="Box"
      title="Nhập dữ liệu sản phẩm từ URL"
      :close-on-click-modal="false"
      width="720px"
    >
      <tao-bao ref="taobaos" v-if="modals" @on-close="onClose"></tao-bao>
    </el-dialog>
    <el-dialog
      :visible.sync="batchModal"
      class="batch-box"
      title="Cài đặt hàng loạt"
      :show-close="true"
      :close-on-click-modal="false"
      width="540px"
    >
      <el-form
        class="batchFormData"
        ref="batchFormData"
        :rules="ruleBatch"
        :model="batchFormData"
        label-width="90px"
        label-position="right"
        @submit.native.prevent
      >
        <el-row :gutter="24">
          <el-col :span="24" v-if="batchType == 1">
            <!--            <el-divider content-position="left">Cài đặt cơ bản</el-divider>-->
            <el-form-item label="Danh mục sản phẩm：" prop="cate_id">
              <!-- <el-select v-model="batchFormData.cate_id" placeholder="Vui lòng chọn danh mục sản phẩm" multiple class="perW20">
                <el-option v-for="item in treeSelect" :disabled="item.pid === 0" :value="item.id" :key="item.id">{{
                  item.html + item.cate_name
                }}</el-option>
              </el-select> -->
              <el-cascader
                v-model="batchFormData.cate_id"
                size="small"
                :options="treeSelect"
                :props="{ multiple: true, emitPath: false, checkStrictly: true }"
                clearable
                style="width: 400px"
              ></el-cascader>
            </el-form-item>
          </el-col>
          <el-col :span="24" v-if="batchType == 2">
            <el-form-item label="Phương pháp hậu cần：" prop="logistics">
              <el-checkbox-group v-model="batchFormData.logistics" @change="logisticsBtn">
                <el-checkbox label="1">Chuyển phát nhanh</el-checkbox>
                <el-checkbox label="2">Đến cửa hàng</el-checkbox>
              </el-checkbox-group>
            </el-form-item>
            <el-form-item label="Cài đặt phí vận chuyển：">
              <el-radio-group v-model="batchFormData.freight">
                <!-- <el-radio :label="1">Miễn phí vận chuyển</el-radio> -->
                <el-radio :label="2">Phí vận chuyển cố định</el-radio>
                <el-radio :label="3">Theo mẫu vận chuyển</el-radio>
              </el-radio-group>
            </el-form-item>
            <el-form-item label="" v-if="batchFormData.freight == 2">
              <div class="acea-row">
                <el-input-number
                  :controls="false"
                  :min="0"
                  v-model="batchFormData.postage"
                  placeholder="Vui lòng nhập số tiền"
                  class="perW20 maxW"
                />
              </div>
            </el-form-item>
            <el-form-item label="" v-if="batchFormData.freight == 3" prop="temp_id">
              <div class="acea-row">
                <el-select v-model="batchFormData.temp_id" clearable placeholder="Vui lòng chọn mẫu vận chuyển sản phẩm" style="width: 414px">
                  <el-option
                    v-for="(item, index) in templateList"
                    :value="item.id"
                    :key="index"
                    :label="item.name"
                  ></el-option>
                </el-select>
              </div>
            </el-form-item>
          </el-col>
          <el-col :span="24" v-if="[3, 4, 5, 6].includes(batchType)">
            <!--            <el-divider content-position="left" v-if="[3, 4, 5, 6].includes(batchType)">Cài đặt tiếp thị</el-divider>-->
            <el-form-item label="Tặng điểm：" prop="give_integral" v-if="batchType == 3">
              <el-input-number
                :controls="false"
                v-model="batchFormData.give_integral"
                :min="0"
                :max="9999999999"
                placeholder="Vui lòng nhập điểm"
                style="width: 100%"
              />
            </el-form-item>
            <el-form-item label="Tặng phiếu giảm giá：" v-if="batchType == 4">
              <div v-if="couponName.length" class="mb20">
                <el-tag closable v-for="(item, index) in couponName" :key="index" @close="handleClose(item)">{{
                  item.title
                }}</el-tag>
              </div>
                <el-button type="primary" v-db-click @click="addCoupon">Chọn phiếu giảm giá</el-button>
            </el-form-item>
            <el-form-item label="Thẻ liên quan：" prop="label_id" v-if="batchType == 5">
              <div class="acea-row label_width">
                <div class="labelInput acea-row row-between-wrapper" v-db-click @click="openLabel">
                  <div style="width: auto">
                    <div v-if="dataLabel.length">
                      <el-tag
                        class="m-r-2"
                        closable
                        v-for="(item, index) in dataLabel"
                        @close="closeLabel(item)"
                        :key="index"
                        >{{ item.label_name }}</el-tag
                      >
                    </div>
                    <span class="span" v-else>Chọn nhãn liên kết người dùng</span>
                  </div>
                  <div class="iconfont iconxiayi"></div>
                </div>
              </div>
            </el-form-item>
            <el-form-item label="Khuyến nghị sản phẩm：" v-if="batchType == 6">
              <el-checkbox-group v-model="batchFormData.recommend">
                <el-checkbox label="is_hot">Mặt hàng bán chạy</el-checkbox>
                <!-- <el-checkbox label="is_benefit">Mặt hàng khuyến mại</el-checkbox> -->
                <el-checkbox label="is_best">Sản phẩm được đề xuất</el-checkbox>
                <el-checkbox label="is_new">Sản phẩm mới đầu tiên</el-checkbox>
                <el-checkbox label="is_good">Sản phẩm được đề xuất</el-checkbox>
              </el-checkbox-group>
            </el-form-item>
          </el-col>
          <el-col :span="24" v-if="batchType == 10">
            <el-form-item label="Bắt đầu tặng quà：">
              <el-switch
                v-model="batchFormData.is_gift"
                class="defineSwitch"
                active-text="Hoạt động"
                inactive-text="Tắt"
                :active-value="1"
                :inactive-value="0"
                size="large"
              >
              </el-switch>
              <div class="tips-info">Sau khi bật tính năng tặng quà, menu dưới cùng của chi tiết sản phẩm trên thiết bị đầu cuối di động sẽ hiển thị nút tặng quà.</div>
            </el-form-item>
            <el-form-item v-if="batchFormData.is_gift" label="Phụ phí quà tặng：">
              <el-input-number
                :controls="false"
                :min="0"
                :max="100000"
                v-model="batchFormData.gift_price"
                placeholder="phụ phí quà tặng"
                class="input-number-unit-class"
                class-unit="đ"
              />
              <div class="tips-info">Khi đặt hàng quà tặng, mặc định sẽ không có phí vận chuyển cho đơn hàng. Khoản phí này có thể được sử dụng để trang trải các chi phí bổ sung như vận chuyển sản phẩm và đóng gói sản phẩm.</div>
            </el-form-item>
          </el-col>
        </el-row>
      </el-form>
      <span slot="footer" class="dialog-footer">
        <el-button v-db-click @click="clearBatchData">Hủy</el-button>
        <el-button type="primary" v-db-click @click="batchSub">Xác nhận</el-button>
      </span>
    </el-dialog>
    <!-- Thẻ sản phẩm -->
    <el-dialog
      :visible.sync="tagShow"
      title="Vui lòng chọn thẻ sản phẩm"
      :show-close="true"
      width="540px"
      :close-on-click-modal="false"
    >
      <goodsLabel
        ref="goodsLabel"
        :defaultLabelList="goodsLabelList"
        @activeLabel="activeGoodsLabel"
        @close="labelClose"
      ></goodsLabel>
    </el-dialog>
    <!-- Thẻ người dùng -->
    <el-dialog
      :visible.sync="labelShow"
      title="Vui lòng chọn nhãn người dùng"
      width="540px"
      :show-close="true"
      :close-on-click-modal="false"
    >
      <userLabel ref="userLabel" @activeData="activeData" @close="labelClose"></userLabel>
    </el-dialog>
    <!-- Cửa sổ bật lên sản phẩm -->
    <div v-if="isProductBox">
      <div class="bg" v-db-click @click="isProductBox = false"></div>
      <goodsDetail :goodsId="goodsId"></goodsDetail>
    </div>
    <coupon-list ref="couponTemplates" @nameId="nameId" :couponids="batchFormData.coupon_ids"></coupon-list>
    <!-- Nhập khẩu sản phẩm -->
    <el-dialog
      :visible.sync="importShow"
      title="Nhập khẩu sản phẩm"
      width="900px"
      :show-close="true"
      :close-on-click-modal="false"
    >
      <goodsImport v-if="importShow" @close="importShow = false"></goodsImport>
    </el-dialog>
    <brokerageSet ref="brokerageSet" :productId="productId"></brokerageSet>
    <vipPriceSet ref="vipPriceSet" :productId="productId"></vipPriceSet>
    <!-- Thẻ sản phẩm -->
    <el-dialog :visible.sync="storeLabelShow" title="Chọn thẻ sản phẩm" width="540">
      <storeLabelList
        v-if="storeLabelShow"
        ref="storeLabel"
        @activeData="activeStoreData"
        @close="storeLabelClose"
      ></storeLabelList>
    </el-dialog>
  </div>
</template>

<script>
import expandRow from './tableExpand.vue';
import attribute from './attribute';
import toExcel from '../../../utils/Excel.js';
import { mapState } from 'vuex';
import taoBao from './taoBao';
import goodsDetail from './components/goodsDetail.vue';
import couponList from '@/components/couponList';
import { exportProductList, exportProductExport } from '@/api/export';
import settings from '@/setting';
import goodsImport from './components/goodsImport.vue';
import brokerageSet from '../components/brokerageSet.vue';
import vipPriceSet from '../components/vipPriceSet.vue';
import {
  getGoodHeade,
  getGoods,
  PostgoodsIsShow,
  cascaderListApi, // Danh sách danh mục
  productShowApi,
  productUnshowApi,
  storeProductApi,
  batchSetting,
  productGetTemplateApi,
  productLabelUseListApi,
  productBatchDelete,
} from '@/api/product';
import userLabel from '@/components/labelList';
import storeLabelList from '@/components/storeLabelList';
import goodsLabel from '@/components/goodsLabel';

export default {
  name: 'product_productList',
  components: {
    expandRow,
    attribute,
    taoBao,
    goodsDetail,
    userLabel,
    couponList,
    goodsImport,
    brokerageSet,
    vipPriceSet,
    storeLabelList,
    goodsLabel,
  },
  computed: {
    ...mapState('userLevel', ['categoryId']),
  },
  data() {
    return {
      routePre: settings.routePre,
      pickerOptions: this.$timeOptions,
      template: false,
      modals: false,
      importShow: false,
      batchModal: false,
      labelShow: false,
      batchType: 1, // Kiểu thiết lập hàng loạt
      batchFormData: {
        cate_id: [],
        logistics: [],
        freight: 2,
        postage: 0,
        temp_id: null,
        give_integral: 0,
        label_id: [],
        coupon_ids: [],
        recommend: [],
      },
      ruleBatch: {},
      couponName: [], // Phiếu giảm giá
      dataLabel: [], // Nhãn
      templateList: [], // Mẫu vận chuyển hàng hóa
      grid: {
        xl: 6,
        lg: 8,
        md: 12,
        sm: 24,
        xs: 24,
      },
      artFrom: {
        page: 1,
        limit: 15,
        cate_id: '',
        type: '1',
        store_name: '',
        spec_type: '',
        logistics: '',
        vip_product: '',
        is_gift: '',
        sales_s: ['', ''],
        stock_s: ['', ''],
        price_s: ['', ''],
        store_label_id: [],
        time: '',
        virtual_type: '',
      },
      list: [],
      tableList: [],
      headeNum: [],
      loading: false,
      data: [],
      total: 0,
      attrTemplate: false,
      ids: [],
      goodsId: '',
      isProductBox: false,
      treeSelect: [],
      multipleSelection: [],
      showBrokerage: false,
      showVipPrice: false,
      storeLabelShow: false,
      tagShow: false,
      productId: 0,
      storeLabelList: [],
      goodsLabelList: [],
      timeVal: [],
      collapse: false,
    };
  },
  watch: {
    $route() {
      if (this.$route.fullPath === this.$routeProStr + '/product/product_list?type=5') {
        this.getPath();
      }
    },
  },
  created() {},
  activated() {
    this.goodHeade();
    this.goodsCategory();
    this.getLabelList();
    if (this.$route.fullPath === this.$routeProStr + '/product/product_list?type=5') {
      this.getPath();
    } else {
      this.getDataList();
    }
  },
  methods: {
    // ngày cụ thể
    onchangeTime(e) {
      this.timeVal = e;
      this.artFrom.time = this.timeVal ? this.timeVal.join('-') : '';
      this.artFrom.page = 1;
      this.getDataList();
    },
    // Cửa sổ bật lên nhãn đóng lại
    storeLabelClose() {
      this.storeLabelShow = false;
    },
    getLabelList() {
      productLabelUseListApi()
        .then((res) => {
          res.data.map((el) => {
            if (el.list && el.list.length) {
              el.list.map((label) => {
                label.active = false;
              });
            }
          });
          this.goodsLabelList = res.data;
        })
        .catch((res) => {
          this.$message.error(res.msg);
        });
    },
    openStoreLabel(row) {
      this.storeLabelShow = true;
      this.$nextTick((e) => {
        this.$refs.storeLabel.storeLabel(JSON.parse(JSON.stringify(this.storeLabelList)));
      });
    },
    closeStoreLabel(label) {
      let index = this.storeLabelList.indexOf(this.storeLabelList.filter((d) => d.id == label.id)[0]);
      this.storeLabelList.splice(index, 1);
      this.getLabelId(this.storeLabelList);
    },
    activeStoreData(storeDataLabel) {
      this.storeLabelShow = false;
      this.storeLabelList = storeDataLabel;
      this.getLabelId(storeDataLabel);
    },
    getLabelId(storeDataLabel) {
      let storeActiveIds = [];
      storeDataLabel.forEach((item) => {
        storeActiveIds.push(item.id);
      });
      this.artFrom.store_label_id = storeActiveIds;
      this.artFrom.page = 1;
      this.getDataList();
    },
    activityDetail(row, type) {
      let name = '';
      if (type === 0) {
        name = 'marketing_storeBargain';
      } else if (type === 1) {
        name = 'marketing_combinalist';
      } else if (type === 2) {
        name = 'marketing_storeSeckill';
      }
      this.$router.push({
        name,
        params: {
          product_id: row.id,
        },
      });
    },
    openModal(row, type) {
      this.productId = row.id;
      this.$refs[type].visible = true;
    },
    batchSub() {
      let data = this.batchFormData;
      data.ids = this.ids;
      data.type = this.batchType;
      let activeIds = [];
      this.dataLabel.forEach((item) => {
        activeIds.push(item.id);
      });
      data.label_id = activeIds;
      if (this.batchType == 2 && !this.batchFormData.logistics.length) {
        return this.$message.warning('Vui lòng chọn phương thức hậu cần');
      }
      batchSetting(data)
        .then((res) => {
          this.$message.success(res.msg);
          this.getDataList();
          this.clearBatchData(false);
          this.ids = [];
        })
        .catch((err) => {
          this.$message.error(err.msg);
        });
    },
    clearBatchData(status) {
      if (!status) {
        this.batchFormData = {
          cate_id: [],
          logistics: [],
          freight: 0,
          postage: null,
          temp_id: null,
          give_integral: null,
          label_id: [],
          coupon_ids: [],
          recommend: [],
          is_gift: null,
          label_list: [],
        };
        this.dataLabel = [];
      }
      this.batchModal = false;
      this.$refs.table.clearSelection();
    },
    // Thiết lập sản phẩm theo lô
    batchSelect(type) {
      if (!this.ids.length) {
        this.$message.warning('Vui lòng chọn sản phẩm bạn muốn sửa đổi');
      } else if (type === 7) {
        this.onDismount();
      } else if (type === 8) {
        this.onShelves();
      } else if (type === 9) {
        this.batchType = type;
        this.tagShow = true;
      } else if (type === 11) {
        this.batchGoodsSetting('Di chuyển Tất cả vào thùng rác', 1);
      } else if (type === 12) {
        this.batchGoodsSetting('Khôi phục các mục đã chọn', 2);
      } else {
        this.batchType = type;
        this.batchModal = true;
        this.productGetTemplate();
      }
    },
    batchGoodsSetting(tit, type) {
      let url = type == 1 ? 'product/product/batch_delete' : 'product/product/batch_recover';
      let delfromData = {
        title: tit,
        url,
        method: 'post',
        ids: {
          ids: this.ids,
        },
        un: 1,
      };
      this.$modalSure(delfromData)
        .then((res) => {
          this.$message.success(res.msg);
          this.goodHeade();
          this.getDataList();
        })
        .catch((res) => {
          this.$message.error(res.msg);
        });
    },
    goodsMove(type) {
      if (type === 1) {
        this.onImport();
      } else {
        this.onExports(2);
      }
    },
    activeData(dataLabel) {
      this.labelShow = false;
      this.dataLabel = dataLabel;
    },
    nameId(id, names) {
      this.batchFormData.coupon_ids = id;
      this.couponName = this.unique(names);
    },
    handleClose(name) {
      let index = this.couponName.indexOf(name);
      this.couponName.splice(index, 1);
      this.formValidate.coupon_ids.splice(index, 1);
    },
    //Sao chép mảng đối tượng；
    unique(arr) {
      const res = new Map();
      return arr.filter((arr) => !res.has(arr.id) && res.set(arr.id, 1));
    },
    // Nhận mẫu vận chuyển；
    productGetTemplate() {
      productGetTemplateApi().then((res) => {
        this.templateList = res.data;
      });
    },
    // Cửa sổ bật lên nhãn đóng lại
    labelClose() {
      this.labelShow = false;
      this.tagShow = false;
    },
    // Chọn thẻ sản phẩm
    activeGoodsLabel(data) {
      this.tagShow = false;
      this.batchFormData.label_list = Array.from(new Set(data));
      this.batchSub();
    },
    look(row) {
      this.goodsId = row.id;
      this.isProductBox = true;
    },
    // Phương pháp hậu cần
    logisticsBtn(e) {
      this.batchFormData.logistics = e;
    },
    // Thẻ người dùng được liên kết
    openLabel() {
      this.labelShow = true;
      // this.$refs.userLabel.setLabel(JSON.parse(JSON.stringify(this.dataLabel)));
    },
    closeLabel(label) {
      let index = this.dataLabel.indexOf(this.dataLabel.filter((d) => d.id == label.id)[0]);
      this.dataLabel.splice(index, 1);
    },
    // thêm phiếu giảm giá
    addCoupon() {
      this.$refs.couponTemplates.isTemplate = true;
      this.$refs.couponTemplates.tableList();
    },
    getPath() {
      this.artFrom.page = 1;
      this.artFrom.type = this.$route.query.type.toString();
      this.getDataList();
    },
    onImport() {
      this.importShow = true;
    },
    // Xuất khẩu
    async onExports(type) {
      let [th, filekey, data, fileName] = [[], [], [], ''];
      let excelData = JSON.parse(JSON.stringify(this.artFrom));
      excelData.page = 1;
      excelData.limit = 50;
      excelData.ids = this.ids;
      for (let i = 0; i < excelData.page + 1; i++) {
        let lebData = await this.getExcelData(excelData, type);
        if (!fileName) fileName = lebData.filename;
        if (!filekey.length) {
          filekey = lebData.fileKey;
        }
        if (!th.length) th = lebData.header;
        if (lebData.export.length) {
          data = data.concat(lebData.export);
          excelData.page++;
        } else {
          this.$exportExcel(th, filekey, fileName, data);
          return;
        }
      }
    },
    getExcelData(excelData, type) {
      let fun = type ? exportProductExport : exportProductList;
      return new Promise((resolve, reject) => {
        fun(excelData).then((res) => {
          resolve(res.data);
        });
      });
    },
    freight() {
      this.$refs.template.isTemplate = true;
    },
    // Danh sách hàng loạt
    onShelves() {
      if (this.ids.length === 0) {
        this.$message.warning('Hãy lựa chọn những sản phẩm bạn muốn đặt lên kệ');
      } else {
        let data = {
          ids: this.ids,
        };
        productShowApi(data)
          .then((res) => {
            this.$message.success(res.msg);
            this.goodHeade();
            this.getDataList();
          })
          .catch((res) => {
            this.$message.error(res.msg);
          });
      }
    },
    // Xóa hàng loạt
    onDismount() {
      if (this.ids.length === 0) {
        this.$message.warning('Vui lòng chọn sản phẩm cần lấy ra khỏi kệ');
      } else {
        let data = {
          ids: this.ids,
        };
        productUnshowApi(data)
          .then((res) => {
            this.$message.success(res.msg);
            this.artFrom.page = 1;
            this.goodHeade();
            this.getDataList();
          })
          .catch((res) => {
            this.$message.error(res.msg);
          });
      }
    },

    // Chọn tất cả
    // onSelectTab (selection) {
    //     let data = []
    //     selection.map((item) => {
    //         data.push(item.id)
    //     })
    //     this.ids = data
    // },
    getRowKey(row) {
      return row.id;
    },
    //  Chọn một hàng
    handleSelectRow(selection) {
      const uniqueArr = [];
      const ids = [];
      for (let i = 0; i < selection.length; i++) {
        const item = selection[i];
        if (!ids.includes(item.id)) {
          uniqueArr.push(item);
          ids.push(item.id);
        }
      }
      this.ids = ids;
      this.multipleSelection = uniqueArr;
    },
    // Sản phẩm taobao đã được thêm thành công
    onClose() {
      this.modals = false;
    },
    // Sao chép taobao
    onCopy() {
      this.$router.push({
        path: this.$routeProStr + '/product/add_product',
        query: { type: -1 },
      });
      // this.modals = true
    },
    // tabchọn
    onClickTab() {
      this.artFrom.page = 1;
      this.multipleSelection = [];
      this.$refs.table.clearSelection();
      this.getDataList();
    },
    // Cây đổ xuống
    handleCheckChange(data) {
      let value = '';
      let title = '';
      this.list = [];
      this.artFrom.cate_id = 0;
      data.forEach((item, index) => {
        value += `${item.id},`;
        title += `${item.title},`;
      });
      value = value.substring(0, value.length - 1);
      title = title.substring(0, title.length - 1);
      this.list.push({
        value,
        title,
      });
      this.artFrom.cate_id = value;
      this.getDataList();
    },
    // Lấy số lượng tiêu đề sản phẩm
    goodHeade() {
      getGoodHeade(this.artFrom)
        .then((res) => {
          this.headeNum = res.data.list;
        })
        .catch((res) => {
          this.$message.error(res.msg);
        });
    },
    // Phân loại sản phẩm；
    goodsCategory() {
      cascaderListApi(1)
        .then((res) => {
          this.treeSelect = res.data;
        })
        .catch((res) => {
          this.$message.error(res.msg);
        });
    },
    // Danh sách sản phẩm；
    getDataList() {
      this.loading = true;
      this.artFrom.cate_id = this.artFrom.cate_id || '';
      getGoods(this.artFrom)
        .then((res) => {
          let data = res.data;
          this.tableList = data.list;
          this.total = res.data.count;
          this.$nextTick(() => {
            //Hãy chắc chắn rằng dom đã được tải
            // this.setChecked();
            this.showSelectData();
          });
          this.loading = false;
        })
        .catch((res) => {
          this.loading = false;
          this.$message.error(res.msg);
        });
    },
    showSelectData() {
      if (this.multipleSelection.length > 0) {
        // Xác định xem dữ liệu đã kiểm tra có tồn tại hay không
        this.tableList.forEach((row) => {
          // Lấy dữ liệu theo yêu cầu của giao diện danh sách dữ liệu
          this.multipleSelection.forEach((item) => {
            // Dữ liệu đã kiểm tra
            if (row.id === item.id) {
              this.$refs.table.toggleRowSelection(item, true); // Nếu có sự chồng chéo, dữ liệu sẽ bị lặp lại.
            }
          });
        });
      }
    },
    // tìm kiếm bảng
    userSearchs() {
      this.artFrom.page = 1;
      this.goodHeade();
      this.getDataList();
    },
    // Trên và ngoài kệ
    changeSwitch(row) {
      PostgoodsIsShow(row.id, row.is_show)
        .then((res) => {
          this.$message.success(res.msg);
          this.goodHeade();
          this.getDataList();
        })
        .catch((res) => {
          row.is_show = !row.is_show ? 1 : 0;
          this.$message.error(res.msg);
        });
    },
    // Xuất dữ liệu；
    exportData: function () {
      let th = ['Tên sản phẩm', 'Giới thiệu sản phẩm', 'Danh mục sản phẩm', 'giá', 'Trong kho', 'Doanh số bán hàng', 'Số lượng người thu gom'];
      let filterVal = ['store_name', 'store_info', 'cate_name', 'price', 'stock', 'sales', 'collect'];
      this.where.page = 'nopage';
      getGoods(this.where).then((res) => {
        let data = res.data.map((v) => FilterVal.map((k) => v[k]));
        let fileTime = Date.parse(new Date());
        let [fileName, fileType, sheetName] = ['Dữ liệu người bán_' + fileTime, 'xlsx', 'Dữ liệu người bán'];
        toExcel({ th, data, fileName, fileType, sheetName });
      });
    },
    // Cửa sổ bật lên thuộc tính；
    attrTap() {
      this.attrTemplate = true;
    },
    changeTemplate(msg) {
      this.attrTemplate = msg;
    },
    // biên tập
    edit(row) {
      this.$router.push({ path: this.$routeProStr + '/product/add_product/' + row.id });
    },
    // xác nhận
    del(row, tit, num) {
      let delfromData = {
        title: tit,
        num: num,
        url: `product/product/${row.id}`,
        method: 'DELETE',
        ids: '',
        un: 1,
      };
      this.$modalSure(delfromData)
        .then((res) => {
          this.$message.success(res.msg);
          this.tableList.splice(num, 1);
          this.goodHeade();
          this.getDataList();
        })
        .catch((res) => {
          this.$message.error(res.msg);
        });
    },
    fullDel(row, tit, num) {
      let delfromData = {
        title: tit,
        num: num,
        url: `product/full_del/${row.id}`,
        method: 'DELETE',
      };
      this.$modalSure(delfromData)
        .then((res) => {
          this.$message.success(res.msg);
          this.tableList.splice(num, 1);
          this.goodHeade();
          this.getDataList();
        })
        .catch((res) => {
          this.$message.error(res.msg);
        });
    },
    // cài lại
    reset(name) {
      this.artFrom = {
        page: 1,
        limit: 15,
        cate_id: '',
        type: '1',
        store_name: '',
        spec_type: '',
        logistics: '',
        vip_product: '',
        is_gift: '',
        sales_s: ['', ''],
        stock_s: ['', ''],
        price_s: ['', ''],
        store_label_id: [],
        time: '',
        virtual_type: '',
      };
      this.storeLabelList = [];
      this.tableList = [];
      this.total = 0;
      this.timeVal = [];
      this.getDataList();
    },
  },
};
</script>
<style scoped lang="scss">
::v-deep .el-tabs__item {
  height: auto !important;
  min-height: 40px;
  line-height: 1.35 !important;
  white-space: normal;
  display: inline-flex;
  align-items: center;
  padding-top: 8px;
  padding-bottom: 8px;
}
::v-deep .ivu-modal-mask {
  z-index: 999 !important;
}

::v-deep .ivu-modal-wrap {
  z-index: 999 !important;
}

.Box {
  ::v-deep .ivu-modal-body {
    height: 700px;
    overflow: auto;
  }
}

.batch-box {
  ::v-deep .ivu-modal-body {
    overflow: auto;
    min-height: 350px;
  }
}

.tabBox_img {
  width: 36px;
  height: 36px;
  border-radius: 4px;
  cursor: pointer;

  img {
    width: 100%;
    height: 100%;
  }
}

.bg {
  position: fixed;
  left: 0;
  top: 0;
  width: 100%;
  height: 100%;
  background: rgba(0, 0, 0, 0.5);
  z-index: 11;
}

::v-deep .happy-scroll-content {
  width: 100%;

  .demo-spin-icon-load {
    animation: ani-demo-spin 1s linear infinite;
  }

  @keyframes ani-demo-spin {
    from {
      transform: rotate(0deg);
    }

    50% {
      transform: rotate(180deg);
    }

    to {
      transform: rotate(360deg);
    }
  }

  .demo-spin-col {
    height: 100px;
    position: relative;
    border: 1px solid #eee;
  }
}

.labelInput {
  border: 1px solid #dcdee2;
  padding: 0 15px 0 10px;
  border-radius: 5px;
  min-height: 30px;
  cursor: pointer;
  font-size: 12px;

  .span {
    color: #c5c8ce;
  }

  .iconxiayi {
    margin-left: 5px;
    font-size: 12px;
  }
}

.date-range-vi {
  width: 280px;
  max-width: 100%;
}
.el-dropdown-link {
  cursor: pointer;
  color: var(--prev-color-primary);
  font-size: 12px;
}
.el-icon-arrow-down {
  font-size: 12px;
}
.el-dropdown-menu__item {
  a {
    color: #606266;
  }
}
.label_width {
  width: 400px;
}
.search-form {
  display: flex;
  justify-content: space-between;
  .search-form-box {
    display: flex;
    flex-wrap: wrap;
    flex: 1;
  }
  .search-form-sub {
    display: flex;
    align-items: baseline;
  }
}
</style>
