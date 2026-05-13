<template>
  <div>
    <div class="tabs">
      <el-tabs v-model="apiType">
        <el-tab-pane label="Giao diện quản lý" name="adminapi"></el-tab-pane>
        <el-tab-pane label="giao diện khách hàng" name="api"></el-tab-pane>
        <el-tab-pane label="Giao diện dịch vụ khách hàng" name="kefuapi"></el-tab-pane>
        <el-tab-pane label="Kết nối API ngoài" name="outapi"></el-tab-pane>
      </el-tabs>
    </div>
    <div class="main" v-loading="winLoading">
      <div class="ivu-mt card-tree b-r-1">
        <div class="tree">
          <div class="main-btn">
            <el-button class="mb5" style="flex: 1" type="primary" v-db-click @click="clickMenu(4)" long
              >Thêm danh mục mới</el-button
            >
            <el-button class="mb5 mr10" type="success" v-db-click @click="syncRoute()">Đồng bộ</el-button>
          </div>

          <vue-tree-list
            class="tree-list"
            ref="treeList"
            @change-name="onChangeName"
            @delete-node="onDel"
            :model="treeData"
            default-tree-node-name="thư mục mặc định"
            default-leaf-node-name="Tên giao diện mặc định"
            v-bind:default-expanded="false"
            :expand-only-one="true"
          >
            <template v-slot:leafNameDisplay="slotProps">
              <div></div>
              <div
                class="tree-node"
                :class="{
                  node: slotProps.model.method,
                  open: formValidate.path == slotProps.model.path && formValidate.method == slotProps.model.method,
                }"
                v-db-click
                @click.stop="onClick(slotProps.model)"
              >
                <span
                  class=""
                  :class="{
                    open: formValidate.path == slotProps.model.path && formValidate.method == slotProps.model.method,
                  }"
                  >{{ slotProps.model.name }}</span
                >
                <el-dropdown
                  size="small"
                  transfer
                  @command="
                    (name) => {
                      clickMenu(name, slotProps.model);
                    }
                  "
                >
                  <span class="el-dropdown-link">
                    <i class="el-icon-more"></i>
                  </span>
                  <template slot="dropdown">
                    <el-dropdown-menu>
                      <el-dropdown-item command="1" v-if="!slotProps.model.method">Giao diện mới</el-dropdown-item>
                      <el-dropdown-item command="2" v-if="!slotProps.model.method">Chỉnh sửa tên danh mục</el-dropdown-item>
                      <el-dropdown-item command="3">Xóa</el-dropdown-item>
                    </el-dropdown-menu>
                  </template>
                </el-dropdown>
              </div>
            </template>
            <!-- Tạo thư mục mới -->

            <span class="icon" slot="addTreeNodeIcon"></span>
            <span class="icon" slot="addLeafNodeIcon"></span>
            <span class="icon" slot="editNodeIcon"></span>
            <span class="icon" slot="delNodeIcon"></span>
            <template v-slot:treeNodeIcon="slotProps">
              <span
                v-if="slotProps.model.method"
                class="req-method"
                :style="{
                  color: methodsColor(slotProps.model.method),
                  'font-weight': slotProps.model.pid == formValidate.pid ? '500' : '500',
                }"
                >{{ slotProps.model.method }}</span
              >

              <!-- <span v-if="slotProps.model.method"></span> -->
            </template>
          </vue-tree-list>
        </div>
      </div>
      <el-card :bordered="false" shadow="never" class="ivu-mt right-card">
        <div class="data">
          <div class="eidt-sub">
            <div class="name">
              {{ formValidate.name }}
            </div>
            <div>
              <el-button class="submission" v-db-click @click="debugging()">Gỡ lỗi</el-button>
              <el-button
                v-if="formValidate.id"
                type="primary"
                class="submission"
                v-db-click
                @click="isEdit = !isEdit"
                >{{ isEdit ? 'Hủy bỏ' : 'Chỉnh sửa' }}</el-button
              >
              <el-button
                v-if="isEdit"
                type="primary"
                class="submission"
                v-db-click
                @click="handleSubmit('formValidate')"
                >Lưu</el-button
              >
            </div>
          </div>
          <el-form
            class="formValidate mt20"
            ref="formValidate"
            :rules="ruleValidate"
            :model="formValidate"
            label-width="120px"
            :label-position="labelPosition"
            @submit.native.prevent
          >
            <el-row :gutter="24">
              <el-col :span="24">
                <div class="title">Thông tin giao diện</div>
                <el-form-item label="Tên giao diện：" prop="name">
                  <el-input
                    v-if="isEdit"
                    class="perW20"
                    type="text"
                    :rows="4"
                    v-model="formValidate.name"
                    placeholder="Vui lòng nhập"
                  />
                  <span v-else>{{ formValidate.name || '' }}</span>
                </el-form-item>
                <el-form-item label="Loại yêu cầu：" prop="name">
                  <el-select v-if="isEdit" v-model="formValidate.method" style="width: 120px">
                    <el-option
                      v-for="(item, index) in requestTypeList"
                      :key="index"
                      :value="item.value"
                      :label="item.label"
                    ></el-option>
                  </el-select>
                  <span v-else class="req-method" :style="'background-color:' + methodColor">{{
                    formValidate.method || ''
                  }}</span>
                </el-form-item>
                <el-form-item label="Mô tả chức năng：" prop="name">
                  <el-input
                    v-if="isEdit"
                    class="perW20"
                    type="textarea"
                    :rows="4"
                    v-model="formValidate.describe"
                    placeholder="Vui lòng nhập"
                  />
                  <span v-else class="text-area">{{ formValidate.describe || '--' }}</span>
                </el-form-item>
                <el-form-item label="Loại：" prop="name" v-if="isEdit">
                  <el-cascader
                    v-model="formValidate.cate_id"
                    size="small"
                    :options="formValidate.cate_tree"
                    :props="{ checkStrictly: true, multiple: false, emitPath: false, value: 'id', label: 'name' }"
                    clearable
                  ></el-cascader>
                </el-form-item>
                <el-form-item label="Nó có công khai không?：" prop="name">
                  <el-switch v-if="isEdit" v-model="formValidate.type" :active-value="1" :inactive-value="0">
                  </el-switch>
                  <span v-else class="text-area">{{ formValidate.type ? 'Đúng' : 'KHÔNG' }}</span>
                </el-form-item>
              </el-col>
            </el-row>
            <el-row :gutter="24">
              <el-col :span="24">
                <div class="title">Phương thức gọi</div>
                <el-form-item label="Địa chỉ định tuyến：" prop="path">
                  <span>{{ formValidate.path || '' }}</span>
                </el-form-item>
                <el-form-item label="Địa chỉ tệp：" prop="path">
                  <span>{{ formValidate.file_path || '' }}</span>
                </el-form-item>
                <el-form-item label="Tên phương thức：" prop="path">
                  <span>{{ formValidate.action || '' }}</span>
                </el-form-item>
                <el-form-item label="Headertham số：">
                  <vxe-table
                    resizable
                    show-overflow
                    keep-source
                    ref="headTable"
                    row-id="id"
                    :print-config="{}"
                    :export-config="{}"
                    :loading="loading"
                    :tree-config="{ transform: true, rowField: 'id', parentField: 'parentId' }"
                    :data="formValidate.header"
                  >
                    <!-- <vxe-column type="checkbox" width="60"></vxe-column> -->
                    <vxe-column field="attribute" width="300" title="Tài sản" tree-node :edit-render="{}">
                      <template #default="{ row }">
                        <vxe-input v-if="isEdit" v-model="row.attribute" type="text"></vxe-input>
                        <span v-else>{{ row.attribute || '' }}</span>
                      </template>
                    </vxe-column>
                    <vxe-column field="type" title="Kiểu" width="200" :edit-render="{}">
                      <template #default="{ row }">
                        <!-- <vxe-select v-if="isEdit" v-model="row.type" type="text" :optionGroups="typeList"></vxe-select> -->
                        <vxe-select v-if="isEdit" v-model="row.type" transfer>
                          <vxe-option
                            v-for="item in typeList"
                            :key="item.value"
                            :value="item.value"
                            :label="item.label"
                          ></vxe-option>
                        </vxe-select>
                        <span v-else>{{ row.type || '' }}</span>

                        <!-- <vxe-select v-model="row.type">
									    <vxe-option v-for="num in 12" :key="num" :value="num" :label="num"></vxe-option>
									  </vxe-select> -->
                      </template>
                    </vxe-column>
                    <vxe-column field="must" title="Yêu cầu" width="100" :edit-render="{}">
                      <template #default="{ row }">
                        <vxe-checkbox
                          v-if="isEdit"
                          v-model="row.must"
                          :unchecked-value="'0'"
                          :checked-value="'1'"
                        ></vxe-checkbox>
                        <span v-else>{{ row.must == '1' ? 'Đúng' : 'KHÔNG' }}</span>
                      </template>
                    </vxe-column>
                    <vxe-column field="trip" title="Minh họa" :edit-render="{}">
                      <template #default="{ row }">
                        <vxe-input v-if="isEdit" v-model="row.trip" type="text"></vxe-input>
                        <span v-else>{{ row.trip || '' }}</span>
                      </template>
                    </vxe-column>
                    <vxe-column title="Thao tác" width="200" v-if="isEdit">
                      <template #default="{ row }">
                        <vxe-button
                          type="text"
                          v-if="row.type === 'array' || row.type === 'object'"
                          status="primary"
                          v-db-click
                          @click="insertRow(row, 'headTable')"
                          >Chèn</vxe-button
                        >
                        <vxe-button type="text" status="primary" v-db-click @click="removeRow(row, 'headTable')"
                          >Xóa</vxe-button
                        >
                      </template>
                    </vxe-column>
                  </vxe-table>

                  <el-button class="mt10" v-if="isEdit" type="primary" v-db-click @click="insertEvent('headTable')"
                    >Thêm thông số</el-button
                  >
                </el-form-item>
                <el-form-item label="Querytham số：">
                  <vxe-table
                    resizable
                    show-overflow
                    keep-source
                    ref="xTable"
                    row-id="id"
                    :print-config="{}"
                    :export-config="{}"
                    :loading="loading"
                    :tree-config="{ transform: true, rowField: 'id', parentField: 'parentId' }"
                    :data="formValidate.query"
                  >
                    <vxe-column field="attribute" width="300" title="Tài sản" tree-node :edit-render="{}">
                      <template #default="{ row }">
                        <vxe-input v-if="isEdit" v-model="row.attribute" type="text"></vxe-input>
                        <span v-else>{{ row.attribute || '' }}</span>
                      </template>
                    </vxe-column>
                    <vxe-column field="type" title="Kiểu" width="200" :edit-render="{}">
                      <template #default="{ row }">
                        <vxe-select v-if="isEdit" v-model="row.type" transfer>
                          <vxe-option
                            v-for="item in typeList"
                            :key="item.value"
                            :value="item.value"
                            :label="item.label"
                          ></vxe-option>
                        </vxe-select>
                        <span v-else>{{ row.type || '' }}</span>
                      </template>
                    </vxe-column>
                    <vxe-column field="must" title="Yêu cầu" width="100" :edit-render="{}">
                      <template #default="{ row }">
                        <vxe-checkbox
                          v-if="isEdit"
                          v-model="row.must"
                          :unchecked-value="'0'"
                          :checked-value="'1'"
                        ></vxe-checkbox>
                        <span v-else>{{ row.must == '1' ? 'Đúng' : 'KHÔNG' }}</span>
                      </template>
                    </vxe-column>
                    <vxe-column field="trip" title="Minh họa" :edit-render="{}">
                      <template #default="{ row }">
                        <vxe-input v-if="isEdit" v-model="row.trip" type="text"></vxe-input>
                        <span v-else>{{ row.trip || '' }}</span>
                      </template>
                    </vxe-column>
                    <vxe-column title="Thao tác" width="200" v-if="isEdit">
                      <template #default="{ row }">
                        <vxe-button
                          type="text"
                          v-if="row.type === 'array' || row.type === 'object'"
                          status="primary"
                          v-db-click
                          @click="insertRow(row, 'xTable')"
                          >Chèn</vxe-button
                        >
                        <vxe-button type="text" status="primary" v-db-click @click="removeRow(row, 'xTable')"
                          >Xóa</vxe-button
                        >
                      </template>
                    </vxe-column>
                  </vxe-table>
                  <el-button class="mt10" v-if="isEdit" type="primary" v-db-click @click="insertEvent('xTable')"
                    >Thêm thông số</el-button
                  >
                </el-form-item>
                <el-form-item label="Bodytham số：">
                  <vxe-table
                    resizable
                    show-overflow
                    keep-source
                    ref="bodyTable"
                    row-id="id"
                    :print-config="{}"
                    :export-config="{}"
                    :loading="loading"
                    :tree-config="{ transform: true, rowField: 'id', parentField: 'parentId' }"
                    :data="formValidate.request"
                  >
                    <!-- <vxe-column type="checkbox" width="60"></vxe-column> -->
                    <vxe-column field="attribute" width="300" title="Tài sản" tree-node :edit-render="{}">
                      <template #default="{ row }">
                        <vxe-input v-if="isEdit" v-model="row.attribute" type="text"></vxe-input>
                        <span v-else>{{ row.attribute || '' }}</span>
                      </template>
                    </vxe-column>
                    <vxe-column field="type" title="Kiểu" width="200" :edit-render="{}">
                      <template #default="{ row }">
                        <!-- <vxe-select v-if="isEdit" v-model="row.type" type="text" :optionGroups="typeList"></vxe-select> -->
                        <vxe-select v-if="isEdit" v-model="row.type" transfer>
                          <vxe-option
                            v-for="item in typeList"
                            :key="item.value"
                            :value="item.value"
                            :label="item.label"
                          ></vxe-option>
                        </vxe-select>
                        <span v-else>{{ row.type || '' }}</span>

                        <!-- <vxe-select v-model="row.type">
                      <vxe-option v-for="num in 12" :key="num" :value="num" :label="num"></vxe-option>
                    </vxe-select> -->
                      </template>
                    </vxe-column>
                    <vxe-column field="must" title="Yêu cầu" width="100" :edit-render="{}">
                      <template #default="{ row }">
                        <vxe-checkbox
                          v-if="isEdit"
                          v-model="row.must"
                          :unchecked-value="'0'"
                          :checked-value="'1'"
                        ></vxe-checkbox>
                        <span v-else>{{ row.must == '1' ? 'Đúng' : 'KHÔNG' }}</span>
                      </template>
                    </vxe-column>
                    <vxe-column field="trip" title="Minh họa" :edit-render="{}">
                      <template #default="{ row }">
                        <vxe-input v-if="isEdit" v-model="row.trip" type="text"></vxe-input>
                        <span v-else>{{ row.trip || '' }}</span>
                      </template>
                    </vxe-column>
                    <vxe-column title="Thao tác" width="200" v-if="isEdit">
                      <template #default="{ row }">
                        <vxe-button
                          type="text"
                          v-if="row.type === 'array' || row.type === 'object'"
                          status="primary"
                          v-db-click
                          @click="insertRow(row, 'bodyTable')"
                          >Chèn</vxe-button
                        >
                        <vxe-button type="text" status="primary" v-db-click @click="removeRow(row, 'bodyTable')"
                          >Xóa</vxe-button
                        >
                      </template>
                    </vxe-column>
                  </vxe-table>

                  <el-button class="mt10" v-if="isEdit" type="primary" v-db-click @click="insertEvent('bodyTable')"
                    >Thêm thông số</el-button
                  >
                </el-form-item>
                <el-form-item label="Trả về tham số：">
                  <vxe-table
                    resizable
                    show-overflow
                    keep-source
                    ref="resTable"
                    row-id="id"
                    :print-config="{}"
                    :export-config="{}"
                    :loading="loading"
                    :tree-config="{ transform: true, rowField: 'id', parentField: 'parentId' }"
                    :data="formValidate.response"
                  >
                    <!-- <vxe-column type="checkbox" width="60"></vxe-column> -->
                    <vxe-column field="attribute" title="Tài sản" width="300" tree-node :edit-render="{}">
                      <template #default="{ row }">
                        <vxe-input v-if="isEdit" v-model="row.attribute" type="text"></vxe-input>
                        <span v-else>{{ row.attribute || '' }}</span>
                      </template>
                    </vxe-column>
                    <vxe-column field="type" title="Kiểu" width="200" :edit-render="{}">
                      <template #default="{ row }">
                        <vxe-select v-if="isEdit" v-model="row.type" transfer>
                          <vxe-option
                            v-for="item in typeList"
                            :key="item.value"
                            :value="item.value"
                            :label="item.label"
                          ></vxe-option>
                        </vxe-select>
                        <span v-else>{{ row.type || '' }}</span>
                      </template>
                    </vxe-column>
                    <!-- <vxe-column field="type" title="Yêu cầu" :edit-render="{}">
                  <template #default="{ row }">
                    <vxe-checkbox v-model="row.must" :unchecked-value="0" :checked-value="1"></vxe-checkbox
                    >{{ row.must }}
                  </template>
                </vxe-column> -->
                    <vxe-column field="trip" title="Minh họa" :edit-render="{}">
                      <template #default="{ row }">
                        <vxe-input v-if="isEdit" v-model="row.trip" type="text"></vxe-input>
                        <span v-else>{{ row.trip || '' }}</span>
                      </template>
                    </vxe-column>
                    <vxe-column title="Thao tác" width="200" v-if="isEdit">
                      <template #default="{ row }">
                        <vxe-button
                          type="text"
                          v-if="row.type === 'array' || row.type === 'object'"
                          status="primary"
                          v-db-click
                          @click="insertRow(row, 'resTable')"
                          >Chèn</vxe-button
                        >
                        <vxe-button type="text" status="primary" v-db-click @click="removeRow(row, 'resTable')"
                          >Xóa</vxe-button
                        >
                      </template>
                    </vxe-column>
                  </vxe-table>
                  <el-button class="mt10" v-if="isEdit" type="primary" v-db-click @click="insertEvent('resTable')"
                    >Thêm thông số</el-button
                  >
                </el-form-item>
              </el-col>
            </el-row>
            <el-row :gutter="24">
              <el-col :span="24">
                <div class="title">Ví dụ cuộc gọi</div>
                <!-- <el-form-item label="Ví dụ về dữ liệu yêu cầu：" prop="request_example">
                    <el-input
                      v-if="isEdit"
                      class="perW20"
                      type="textarea"
                      :rows="4"
                      v-model.trim="formValidate.request_example"
                      placeholder="Vui lòng nhập"
                    />
                    <span v-else class="text-area">{{ formValidate.request_example || '' }}</span>
                  </el-form-item> -->
                <el-form-item v-if="formValidate.response_example" label="Ví dụ về dữ liệu trả về：" prop="response_example">
                  <el-collapse v-for="(item, index) in formValidate.response_example" accordion :key="index">
                    <el-collapse-item>
                      <template slot="title">
                        {{ item.name || '' }}
                      </template>
                      <el-input
                        v-if="isEdit"
                        class="perW20"
                        type="textarea"
                        :rows="4"
                        v-model.trim="item.data"
                        placeholder="Vui lòng nhập"
                      />
                      <span v-else class="text-area">{{ item.data || '' }}</span>
                    </el-collapse-item>
                  </el-collapse>
                </el-form-item>
                <el-form-item label="Mã lỗi：">
                  <vxe-table
                    resizable
                    show-overflow
                    keep-source
                    ref="codeTable"
                    row-id="id"
                    is-tree-view
                    :print-config="{}"
                    :export-config="{}"
                    :loading="loading"
                    :tree-config="{ rowField: 'id', parentField: 'parentId' }"
                    :data="formValidate.error_code"
                  >
                    <!-- <vxe-column type="checkbox" width="60"></vxe-column> -->
                    <vxe-column field="code" title="Mã lỗi" tree-node :edit-render="{}">
                      <template #default="{ row }">
                        <vxe-input v-if="isEdit" v-model="row.code" type="text"></vxe-input>
                        <span v-else>{{ row.code || '' }}</span>
                      </template>
                    </vxe-column>
                    <vxe-column field="value" title="Giá trị mã lỗi" :edit-render="{}">
                      <template #default="{ row }">
                        <vxe-input v-if="isEdit" v-model="row.value" type="text"></vxe-input>
                        <span v-else>{{ row.value || '' }}</span>
                      </template>
                    </vxe-column>
                    <vxe-column field="solution" title="Giải pháp" :edit-render="{}">
                      <template #default="{ row }">
                        <vxe-input v-if="isEdit" v-model="row.solution" type="text"></vxe-input>
                        <span v-else>{{ row.solution || '' }}</span>
                      </template>
                    </vxe-column>
                    <vxe-column title="Thao tác" v-if="isEdit">
                      <template #default="{ row }">
                        <vxe-button type="text" status="primary" v-db-click @click="removeRow(row, 'codeTable')"
                          >Xóa</vxe-button
                        >
                      </template>
                    </vxe-column>
                  </vxe-table>
                  <el-button class="mt10" v-if="isEdit" type="primary" v-db-click @click="insertEvent('codeTable')"
                    >Thêm thông số</el-button
                  >
                </el-form-item>
              </el-col>
            </el-row>
            <!-- <el-row :gutter="24" >
              <el-col :span="24">
                <el-form-item>
                  <el-button type="primary" class="submission" v-db-click @click="handleSubmit('formValidate')">Lưu</el-button>
                </el-form-item>
              </el-col>
            </el-row> -->
          </el-form>
        </div>
        <!-- <div v-else class="nothing">
          <div class="box" v-db-click @click="clickMenu(4)">
            <div class="icon">
              <Icon type="ios-folder" />
            </div>
            <div class="text">Tạo tập tin mới</div>
          </div>
          <div class="box" v-db-click @click="clickMenu(1)">
            <div class="icon">
              <Icon type="logo-linkedin" />
            </div>
            <div class="text">Tạo giao diện mới</div>
          </div>
        </div> -->
      </el-card>
    </div>
    <el-dialog :visible.sync="nameModal" width="470px" title="Tên nhóm">
      <label>Tên nhóm：</label>
      <el-input v-model="value" placeholder="Vui lòng nhập tên nhóm" style="width: 85%" />
      <span slot="footer" class="dialog-footer">
        <el-button v-db-click @click="nameModal = false">Hủy bỏ</el-button>
        <el-button type="primary" v-db-click @click="asyncOK">Chắc chắn</el-button>
      </span>
    </el-dialog>
    <el-drawer
      :visible.sync="debuggingModal"
      :title="formValidate.name"
      size="70%"
      :wrapperClosable="false"
      :loading="loading"
    >
      <debugging
        v-if="debuggingModal"
        :formValidate="formValidate"
        :typeList="intTypeList"
        :requestTypeList="requestTypeList"
        :apiType="apiType"
      />
    </el-drawer>
  </div>
</template>

<script>
import {
  routeCate,
  syncRoute,
  routeList,
  routeDet,
  routeSave,
  interfaceEditName,
  routeDel,
  routeEdit,
  routeCateDel,
} from '@/api/systemBackendRouting';
import { VueTreeList, Tree, TreeNode } from 'vue-tree-list';
import debugging from './debugging.vue';

import { mapState } from 'vuex';
export default {
  name: 'systemOutInterface',
  components: {
    VueTreeList,
    debugging,
  },
  data() {
    return {
      value: '',
      isEdit: false,
      nameModal: false,
      debuggingModal: false,
      formValidate: {},
      grid: {
        xl: 7,
        lg: 7,
        md: 12,
        sm: 24,
        xs: 24,
      },
      ruleValidate: {
        title: [{ message: 'Vui lòng nhập mô tả chính xác (Không thể vượt quá 200 chữ số)', trigger: 'blur', max: 200 }],
      },
      loading: false,
      intTypeList: [
        {
          value: 'string',
          label: 'String',
        },
        // {
        //   value: 'array',
        //   label: 'Array',
        // },
        // {
        //   value: 'object',
        //   label: 'Object',
        // },
        {
          value: 'number',
          label: 'Number',
        },
        {
          value: 'boolean',
          label: 'Boolean',
        },
        {
          value: 'null',
          label: 'Null',
        },
        {
          value: 'any',
          label: 'Any',
        },
      ],
      typeList: [
        {
          value: 'string',
          label: 'String',
        },
        {
          value: 'array',
          label: 'Array',
        },
        {
          value: 'object',
          label: 'Object',
        },
        {
          value: 'number',
          label: 'Number',
        },
        {
          value: 'boolean',
          label: 'Boolean',
        },
        {
          value: 'null',
          label: 'Null',
        },
        {
          value: 'any',
          label: 'Any',
        },
      ],
      requestTypeList: [
        {
          value: 'GET',
          label: 'GET',
        },
        {
          value: 'POST',
          label: 'POST',
        },
        {
          value: 'DELETE',
          label: 'DELETE',
        },
        {
          value: 'PUT',
          label: 'PUT',
        },
      ],
      contextData: null, //Nhấp chuột phải vào điều hướng bên trái là đối tượng dữ liệu được tạo
      treeData: undefined,
      buttonProps: {
        type: 'default',
        size: 'small',
      },
      methodColor: '#fff',
      apiType: 'adminapi',
      paramsId: 0,
      winLoading: false,
    };
  },
  watch: {
    ['formValidate.method']: {
      deep: true,
      handler(newVal, oldVal) {
        if (newVal) {
          let method = newVal.toUpperCase();
          if (method == 'GET') {
            this.methodColor = '#61affe';
          } else if (method == 'POST') {
            this.methodColor = '#49cc90';
          } else if (method == 'PUT') {
            this.methodColor = '#fca130';
          } else if (method == 'DEL' || method == 'DELETE') {
            this.methodColor = '#f93e3e';
          }
        }
      },
    },
    apiType(newVal) {
      if (newVal) {
        this.winLoading = true;
        this.getInterfaceList('one');
      }
    },
    isEdit(newVal) {
      if (newVal) {
        this.formValidate.response_example.map((e) => {
          e.data = JSON.stringify(e.data);
        });
      } else {
        this.formValidate.response_example.map((e) => {
          e.data = JSON.parse(e.data);
        });
      }
    },
  },
  computed: {
    ...mapState('media', ['isMobile']),
    labelWidth() {
      return this.isMobile ? undefined : '50px';
    },
    labelPosition() {
      return this.isMobile ? 'top' : 'right';
    },
  },
  created() {
    this.getInterfaceList('one');
  },
  methods: {
    syncRoute() {
      this.$msgbox({
        title: 'Đồng bộ hóa ngay bây giờ',
        message: 'Sau khi đồng bộ hóa, các giao diện mới trong tệp định tuyến sẽ được thêm vào danh sách giao diện và các tuyến đường đã xóa trong tệp định tuyến sẽ bị xóa đồng thời trong danh sách giao diện.',
        showCancelButton: true,
        cancelButtonText: 'Hủy bỏ',
        confirmButtonText: 'Chắc chắn',
        iconClass: 'el-icon-warning',
        confirmButtonClass: 'btn-custom-cancel',
      })
        .then(() => {
          syncRoute(this.apiType).then((res) => {
            this.getInterfaceList('one');
            this.$message.success(res.msg);
          });
        })
        .catch(() => {});
    },
    debugging() {
      this.debuggingModal = true;
    },
    onClicksss(e) {},
    methodsColor(newVal) {
      let method = newVal.toUpperCase();
      if (method == 'GET') {
        return '#61affe';
      } else if (method == 'POST') {
        return '#49cc90';
      } else if (method == 'PUT') {
        return '#fca130';
      } else if (method == 'DEL' || method == 'DELETE') {
        return '#f93e3e';
      }
    },
    insertBefore(params) {},
    insertAfter(params) {},
    moveInto(params) {},
    async addTableData() {
      const { row: data } = await $table.insertAt(newRow, -1);
      await $table.setActiveCell(data, 'name');
    },
    getInterfaceList(disk_type) {
      try {
        routeList(this.apiType)
          .then((res) => {
            if (res.data.length) {
              res.data[0].expand = false;
              this.treeData = new Tree(res.data);
              let i;
              this.$nextTick((e) => {
                if (disk_type) {
                  const expanders = document.querySelectorAll('.vtl-icon-caret-right');
                  if (
                    res.data[0].children &&
                    res.data[0].children[0].children &&
                    res.data[0].children[0].children.length
                  ) {
                    expanders[0] && expanders[0].click();
                    expanders[1] && expanders[1].click();
                    i = res.data[0].children[0].children[0];
                  } else {
                    expanders[0] && expanders[0].click();
                    i = res.data[0].children[0];
                  }
                  this.onClick(i);
                }
              });
            } else {
              // this.$refs.treeList.clear();
              this.treeData = new Tree({});
              this.formValidate = {};
            }
            this.winLoading = false;
          })
          .catch((err) => {
            this.winLoading = false;
            this.$message.error(err.msg);
          });
      } catch (error) {
        console.log(error);
      }
    },
    onClick(params) {
      try {
        if (params.method) {
          this.isEdit = false;
          this.paramsId = params.id;
          this.getRoteData(params.id);
        }
      } catch (error) {}
    },
    getRoteData(id) {
      routeDet(id)
        .then((res) => {
          this.formValidate = res.data;
        })
        .catch((err) => {
          this.$message.error(err.msg);
        });
    },
    async handleSubmit() {
      if (!this.formValidate.name) {
        return this.$message.warning('Vui lòng nhập tên giao diện');
      } else if (!this.formValidate.method) {
        return this.$message.warning('Vui lòng chọn loại yêu cầu');
      } else if (!this.formValidate.path) {
        return this.$message.warning('Vui lòng nhập địa chỉ định tuyến');
      }
      this.formValidate.request = await this.$refs.bodyTable.getTableData().tableData;
      this.formValidate.response = await this.$refs.resTable.getTableData().tableData;
      this.formValidate.error_code = await this.$refs.codeTable.getTableData().tableData;
      this.formValidate.header = await this.$refs.headTable.getTableData().tableData;
      this.formValidate.query = await this.$refs.xTable.getTableData().tableData;
      this.formValidate.apiType = this.apiType;
      this.formValidate.response_example.map((e) => {
        e.data = JSON.parse(e.data);
      });
      await routeSave(this.formValidate)
        .then((res) => {
          this.$message.success(res.msg);
          this.getRoteData(this.paramsId);
          this.isEdit = false;
        })
        .catch((err) => {
          this.$message.error(err.msg);
        });
    },
    async insertEvent(type) {
      const $table = this.$refs[type];
      let newRow;
      if (type == 'xTable') {
        newRow = {
          attribute: '',
          type: '',
          must: 0,
          trip: '',
        };
      } else if (type == 'resTable') {
        newRow = {
          attribute: '',
          type: '',
          trip: '',
        };
      } else {
        newRow = {
          code: '',
          value: '',
          solution: '',
        };
      }
      // $table.insert(newRow).then(({ row }) => $table.setEditRow(row, -1));
      const { row: data } = await $table.insertAt(newRow, -1);
      await $table.setActiveCell(data, 'name');
    },
    async insertRow(currRow, type) {
      const $table = this.$refs[type];
      // Nếu null, chèn vào đầu nút đích
      // Nếu -1, chèn vào cuối nút đích
      // Nếu là hàng, có một nút mục tiêu hợp lệ được chèn vào vị trí hàng
      let record;
      if (type == 'xTable') {
        record = {
          attribute: '',
          type: '',
          must: 0,
          trip: '',
          id: Date.now(),
          parentId: currRow.id, // Bạn cần chỉ định nút cha và tự động chèn nó vào nút.
        };
      } else if (type == 'resTable') {
        record = {
          attribute: '',
          type: '',
          trip: '',
          id: Date.now(),
          parentId: currRow.id, // Bạn cần chỉ định nút cha và tự động chèn nó vào nút.
        };
      } else {
        record = {
          code: '',
          value: '',
          solution: '',
          id: Date.now(),
          parentId: currRow.id, // Bạn cần chỉ định nút cha và tự động chèn nó vào nút.
        };
      }
      const { row: newRow } = await $table.insertAt(record, -1);
      await $table.setTreeExpand(currRow, true); // Mở rộng nút cha
      await $table.setActiveRow(newRow); // Chèn nút con
    },
    async removeRow(row, type) {
      const $table = this.$refs[type];
      await $table.remove(row);
    },
    // Sửa đổi tên
    add() {
      this.value = '';
      this.formValidate.id = 0;
      this.nameModal = true;
    },
    // bấm vào menu
    clickMenu(name, params) {
      if (name == 1) {
        this.formValidate = {};
        this.formValidate.cate_id = params ? params.id : 0;
        this.formValidate.id = 0;
        this.isEdit = true;
      } else if (name == 2) {
        // this.value = params.name || '';
        // this.formValidate.cate_id = params ? params.id : 0;
        // this.nameModal = true;
        // this.onEdit(params);
        this.$modalForm(routeEdit(params.id, this.apiType)).then(() => this.getInterfaceList());
      } else if (name == 3) {
        this.onDel(params);
      } else if (name == 4) {
        // this.add();
        this.$modalForm(routeCate(this.apiType)).then(() => this.getInterfaceList());
      }
    },

    addFac(params) {
      this.formValidate = {
        id: params ? params.id : 0,
      };
      this.isEdit = true;
    },
    asyncOK() {
      let data = {
        id: this.formValidate.id || 0,
        type: 0,
        name: this.value,
      };
      routeSave(data)
        .then((res) => {
          this.$message.success(res.msg);
          this.getInterfaceList();
        })
        .catch((err) => {
          this.$message.error(err.msg);
        });
    },
    //Sự kiện nhấp chuột phải vào thanh bên
    handleContextMenu(data, event, position) {
      position.left = Number(position.left.slice(0, -2)) + 75 + 'px';
      this.contextData = data;
    },
    handleContextCreateFolder() {},
    handleContextCreateFile() {},
    // Hiển thị tùy chỉnh
    renderContent(h, { root, node, data }) {
      let that = this;
      return h(
        'span',
        {
          style: {
            display: 'inline-block',
            width: '100%',
          },
        },
        [
          h('span', [
            h(resolveComponent('Icon'), {
              type: 'ios-paper-outline',
              style: {
                marginRight: '8px',
              },
            }),
            h('span', data.title),
          ]),
          h(
            'span',
            {
              style: {
                display: 'inline-block',
                float: 'right',
                marginRight: '32px',
              },
            },
            [
              h(resolveComponent('Button'), {
                ...this.buttonProps,
                icon: 'ios-add',
                style: {
                  marginRight: '8px',
                },
                onClick: () => {
                  this.append(data);
                },
              }),
              h(resolveComponent('Button'), {
                ...this.buttonProps,
                icon: 'ios-remove',
                onClick: () => {
                  this.remove(root, node, data);
                },
              }),
            ],
          ),
        ],
      );
    },
    /**
     * Sự kiện nhấp vào thanh bên
     * @param {Object} data
     */
    clickDir(data, root, node) {
      let that = this;
      that.navItem = data;
      that.pathname = data.pathname;
    },
    append(data) {
      const children = data.children || [];
      children.push({
        title: 'appended node',
        expand: true,
      });
      this.$set(data, 'children', children);
    },
    remove(root, node, data) {
      const parentKey = root.find((el) => el === node).parent;
      const parent = root.find((el) => el.nodeKey === parentKey).node;
      const index = parent.children.indexOf(data);
      parent.children.splice(index, 1);
    },
    onMouseOver(root, node, data, e, d) {
      console.log(root, node, data);
    },
    //
    onDel(node) {
      let method = node.cate_id ? routeDel : routeCateDel;
      this.$msgbox({
        title: 'gợi ý',
        message: 'Việc xóa không thể khôi phục được, vui lòng xác nhận trước khi xóa.！',
        showCancelButton: true,
        cancelButtonText: 'Hủy bỏ',
        confirmButtonText: 'Chắc chắn',
        iconClass: 'el-icon-warning',
        confirmButtonClass: 'btn-custom-cancel',
      })
        .then(() => {
          method(node.id)
            .then((res) => {
              this.$message.success(res.msg);
              node.remove();
            })
            .catch((err) => {
              this.$message.error(err.msg);
            });
        })
        .catch(() => {});
    },

    onChangeName(params) {
      if (params.eventType == 'blur') {
        let data = {
          name: params.newName,
          id: params.id,
        };
        interfaceEditName(data)
          .then((res) => {
            this.$message.success(res.msg);
          })
          .catch((err) => {
            this.$message.error(err.msg);
          });
      }
    },

    onAddNode(params) {
      // this.$router.push({
      //   path: '/admin/setting/system_out_interface/add',
      //   query: {
      //     pid: params.pid,
      //   },
      // });
    },

    addNode() {
      var node = new TreeNode({ name: 'new node', isLeaf: false });
      if (!this.data.children) this.data.children = [];
      this.data.addChildren(node);
    },

    getNewTree() {
      var vm = this;
      function _dfs(oldNode) {
        var newNode = {};

        for (var k in oldNode) {
          if (k !== 'children' && k !== 'parent') {
            newNode[k] = oldNode[k];
          }
        }

        if (oldNode.children && oldNode.children.length > 0) {
          newNode.children = [];
          for (var i = 0, len = oldNode.children.length; i < len; i++) {
            newNode.children.push(_dfs(oldNode.children[i]));
          }
        }
        return newNode;
      }

      vm.newTree = _dfs(vm.data);
    },
  },
};
</script>

<style lang="scss" scoped>
.reset {
  margin-left: 10px;
}
.b-r-1 {
  border-right: 1px solid #f2f2f2;
}
.card-tree {
  background: #fff;
  height: 72px;
  box-sizing: border-box;
  overflow-x: scroll; /* Đặt cuộn tràn */
  white-space: nowrap;
  overflow-y: hidden;
  /* Ẩn thanh cuộn */
  border-radius: 4px;
  scrollbar-width: none; /* firefox */
  -ms-overflow-style: none; /* IE 10+ */
}
.card-tree::-webkit-scrollbar {
  display: none; /* Chrome Safari */
}
::v-deep .el-tabs__item {
  height: 54px !important;
  line-height: 54px !important;
}
.tabs {
  background: #fff;
  padding-left: 20px;
  border-radius: 5px 5px 0 0;
}
.main {
  width: 100%;
  display: flex;
  padding-bottom: 16px;
  background: #fff;
  border-radius: 6px;
  .main-btn {
    display: flex;
    position: sticky;
    padding: 0px 5px 0 15px;
    width: 100%;
    background: #fff;
    top: 0px;
    background-color: rgba(255, 255, 255, 0.6);
    backdrop-filter: blur(4px);
    z-index: 99;
  }
  .card-tree {
    width: 290px;
    height: calc(100vh - 205px);
    overflow-y: scroll;
  }
  ::v-deep .tree {
    .tree-list {
      margin-left: 10px;
      padding: 0 15px;
      margin-top: 10px;
    }
    .vtl-caret {
      padding-right: 2px;
    }
    .req-method {
      display: block;
      padding: 0px 2px;
      font-size: 13px;
      line-height: 13px;
      margin-right: 5px;
      border-radius: 4px;

      text-transform: uppercase;
    }
    .tree-node {
      display: flex;
      align-items: center;
      justify-content: space-between;
      cursor: pointer;

      padding: 3px 7px 3px 0;
    }
    .node {
    }
    .open {
      font-weight: 500;
      color: #333;
    }
  }
  ::v-deep .vtl-node-main .vtl-operation {
    position: absolute;
    right: 20px;
  }
  ::v-deep .vtl-node-content {
    width: 100%;
  }
  .pop-menu {
    display: flex;
    justify-content: space-between;
  }
  ::v-deep .vtl-node-content .add {
    display: none;
    margin-right: 10px;
  }
  ::v-deep .vtl-node-content:hover .add {
    display: flex;
    justify-content: center;
    align-items: center;
    border-radius: 50%;
    width: 18px;
    height: 18px;
  }
  ::v-deep .vtl-node-content:hover .add:hover {
    background-color: #fff;
    .pop-menu {
      font-size: 16px;
    }
  }
  ::v-deep .vtl-node-main {
    padding: 3px 0;
  }
  ::v-deep .line1 {
    display: table-caption;
    white-space: nowrap;
    width: 120px;
    overflow: hidden;
    text-overflow: ellipsis;
  }
  ::v-deep .ivu-form-item {
    margin-bottom: 10px;
  }
  .right-card {
    flex: 1;
    ::v-deep .el-card__body {
      max-height: calc(100vh - 205px);
      overflow-y: scroll;
      padding-bottom: 16px;
    }
    ::v-deep .el-form-item--small.el-form-item {
      margin-bottom: 6px;
    }
  }
  .data {
    flex: 1;
    .req-method {
      text-transform: uppercase;
      border-radius: 4px;
      color: #fff;
      padding: 3px 7px;
    }
    .eidt-sub {
      display: flex;
      justify-content: space-between;
      .name {
        font-size: 20px;
        font-weight: 500;
      }
    }
    .title {
      font-size: 16px;
      font-weight: 500;
      margin-bottom: 15px;
    }
    .perW20 {
      width: 500px;
    }
    .text-area {
      white-space: pre-wrap;
      word-break: break-word;
    }
  }
  ::v-deep .ivu-tree-title {
    width: 100% !important;
  }
  ::v-deep .vtl-tree-margin {
    margin-left: 15px;
  }
  ::v-deep .ivu-btn-icon-only.ivu-btn-small {
    width: 28px;
  }
  ::v-deep .tree-node > Span {
    font-size: 14px;
  }
  ::v-deep .tree-node.node > span {
    font-size: 13px;
  }
  .nothing {
    display: flex;
    align-items: center;
    justify-content: center;
    min-height: 800px;
    .box:hover {
      border: 1px solid pink;
    }
    .box {
      display: flex;
      align-items: center;
      justify-content: center;
      flex-direction: column;
      width: 150px;
      height: 200px;
      margin: 0 20px;
      border-radius: 10px;
      cursor: pointer;
      overflow: hidden;
      border: 1px solid #fff;
      .icon {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 100%;
        height: 150px;
        font-size: 40px;
        color: #2d8cf0;
        background: #f1f1f1;
      }
      .text {
        width: 100%;
        height: 50px;
        background: #ddd;
        text-align: center;
        line-height: 50px;
        font-size: 14px;
        font-weight: 500;
      }
    }
  }
}
</style>
