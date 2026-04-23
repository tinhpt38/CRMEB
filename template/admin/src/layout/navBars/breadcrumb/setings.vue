<template>
  <div class="layout-breadcrumb-seting">
    <el-drawer
      title="Trình chỉnh sửa chủ đề"
      :visible.sync="getThemeConfig.isDrawer"
      direction="rtl"
      destroy-on-close
      size="320px"
      @close="onDrawerClose"
    >
      <el-scrollbar class="layout-breadcrumb-seting-bar el-main">
        <!-- Chuyển đổi bố cục -->
        <el-divider :content-position="contentPosotion">{{ $t('message.layout.sixTitle') }}</el-divider>
        <div class="layout-drawer-content-flex">
          <!-- defaults cách trình bày -->
          <div
            class="layout-drawer-content-item"
            :class="{ 'drawer-layout-active': getThemeConfig.layout === 'defaults' }"
            @click="onSetLayout('defaults')"
          >
            <section class="el-container el-circular">
              <aside class="el-aside w10 mr5" style="width: 17px"></aside>
              <section class="el-container is-vertical">
                <header class="el-header mb5" style="height: 10px"></header>
                <main class="el-main"></main>
              </section>
            </section>
          </div>

          <!-- columns cách trình bày -->
          <div
            class="layout-drawer-content-item"
            :class="{ 'drawer-layout-active': getThemeConfig.layout === 'columns' }"
            @click="onSetLayout('columns')"
          >
            <section class="el-container el-circular">
              <aside class="el-aside mr5" style="width: 10px"></aside>
              <aside class="el-aside-dark mr5" style="width: 17px"></aside>
              <section class="el-container is-vertical">
                <header class="el-header mb5" style="height: 10px"></header>
                <main class="el-main"></main>
              </section>
            </section>
          </div>
          <!-- classic cách trình bày -->
          <div
            class="layout-drawer-content-item"
            :class="{ 'drawer-layout-active': getThemeConfig.layout === 'classic' }"
            @click="onSetLayout('classic')"
          >
            <section class="el-container is-vertical el-circular">
              <header class="el-aside mb5" style="height: 10px"></header>
              <section class="el-container">
                <aside class="el-aside-dark mr5" style="width: 17px"></aside>
                <section class="el-container is-vertical">
                  <main class="el-main"></main>
                </section>
              </section>
            </section>
          </div>

          <!-- transverse cách trình bày -->
          <div
            class="layout-drawer-content-item"
            :class="{ 'drawer-layout-active': getThemeConfig.layout === 'transverse' }"
            @click="onSetLayout('transverse')"
          >
            <section class="el-container is-vertical el-circular">
              <header class="el-aside mb5" style="height: 10px"></header>
              <section class="el-container">
                <section class="el-container is-vertical">
                  <main class="el-main"></main>
                </section>
              </section>
            </section>
          </div>
        </div>
        <!-- Cài đặt giao diện -->
        <el-divider :content-position="contentPosotion">{{ $t('message.layout.threeTitle') }}</el-divider>
        <div class="layout-breadcrumb-seting-bar-flex mb10">
          <div class="layout-breadcrumb-seting-bar-flex-label">{{ $t('message.layout.themeStyle') }}</div>
          <div class="layout-breadcrumb-seting-bar-flex-value">
            <el-select
              v-model="getThemeConfig.themeStyle"
              placeholder="Vui lòng chọn"
              size="mini"
              style="width: 90px"
              @change="setLocalTheme"
            >
              <el-option label="xanh đen" value="theme-1"></el-option>
              <el-option label="xanh và trắng" value="theme-2"></el-option>
              <el-option label="xanh đen" value="theme-3"></el-option>
              <el-option label="Xanh và trắng" value="theme-4"></el-option>
              <el-option label="tím đen" value="theme-5"></el-option>
              <el-option label="Tím và trắng" value="theme-6"></el-option>
              <el-option label="đỏ đen" value="theme-7"></el-option>
              <el-option label="đỏ và trắng" value="theme-8"></el-option>
              <el-option label="Độ dốc" value="theme-9" v-if="getThemeConfig.layout === 'columns'"></el-option>
            </el-select>
          </div>
        </div>

        <div
          class="layout-breadcrumb-seting-bar-flex"
          v-if="getThemeConfig.layout === 'columns' || getThemeConfig.layout === 'defaults'"
        >
          <div class="layout-breadcrumb-seting-bar-flex-label">{{ $t('message.layout.threeIsCollapse') }}</div>
          <div class="layout-breadcrumb-seting-bar-flex-value">
            <el-switch v-model="getThemeConfig.isCollapse" :width="35" @change="setLocalThemeConfig"> </el-switch>
          </div>
        </div>
        <div class="layout-breadcrumb-seting-bar-flex mt15">
          <div class="layout-breadcrumb-seting-bar-flex-label">{{ $t('message.layout.threeIsUniqueOpened') }}</div>
          <div class="layout-breadcrumb-seting-bar-flex-value">
            <el-switch v-model="getThemeConfig.isUniqueOpened" :width="35" @change="setLocalThemeConfig"> </el-switch>
          </div>
        </div>
        <div class="layout-breadcrumb-seting-bar-flex mt15">
          <div class="layout-breadcrumb-seting-bar-flex-label">{{ $t('message.layout.threeIsFixedHeader') }}</div>
          <div class="layout-breadcrumb-seting-bar-flex-value">
            <el-switch v-model="getThemeConfig.isFixedHeader" :width="35" @change="setLocalThemeConfig"> </el-switch>
          </div>
        </div>

        <!-- Giao diện hiển thị -->
        <el-divider :content-position="contentPosotion">{{ $t('message.layout.fourTitle') }}</el-divider>
        <div class="layout-breadcrumb-seting-bar-flex">
          <div class="layout-breadcrumb-seting-bar-flex-label">{{ $t('message.layout.fourIsShowLogo') }}</div>
          <div class="layout-breadcrumb-seting-bar-flex-value">
            <el-switch v-model="getThemeConfig.isShowLogo" :width="35" @change="setLocalThemeConfig"> </el-switch>
          </div>
        </div>
        <div
          class="layout-breadcrumb-seting-bar-flex mt15"
          :style="{ opacity: getThemeConfig.layout === 'classic' || getThemeConfig.layout === 'transverse' ? 0.5 : 1 }"
        >
          <div class="layout-breadcrumb-seting-bar-flex-label">{{ $t('message.layout.fourIsBreadcrumb') }}</div>
          <div class="layout-breadcrumb-seting-bar-flex-value">
            <el-switch
              v-model="getThemeConfig.isBreadcrumb"
              :disabled="getThemeConfig.layout === 'classic' || getThemeConfig.layout === 'transverse'"
              :width="35"
              @change="setLocalThemeConfig"
            >
            </el-switch>
          </div>
        </div>
        <div class="layout-breadcrumb-seting-bar-flex mt15">
          <div class="layout-breadcrumb-seting-bar-flex-label">{{ $t('message.layout.fourIsBreadcrumbIcon') }}</div>
          <div class="layout-breadcrumb-seting-bar-flex-value">
            <el-switch v-model="getThemeConfig.isBreadcrumbIcon" :width="35" @change="setLocalThemeConfig"> </el-switch>
          </div>
        </div>
        <div class="layout-breadcrumb-seting-bar-flex mt15">
          <div class="layout-breadcrumb-seting-bar-flex-label">{{ $t('message.layout.fourIsTagsview') }}</div>
          <div class="layout-breadcrumb-seting-bar-flex-value">
            <el-switch v-model="getThemeConfig.isTagsview" :width="35" @change="setLocalThemeConfig"> </el-switch>
          </div>
        </div>
        <div class="layout-breadcrumb-seting-bar-flex mt15">
          <div class="layout-breadcrumb-seting-bar-flex-label">{{ $t('message.layout.fourIsFooter') }}</div>
          <div class="layout-breadcrumb-seting-bar-flex-value">
            <el-switch v-model="getThemeConfig.isFooter" :width="35" @change="setLocalThemeConfig"> </el-switch>
          </div>
        </div>
        <div class="layout-breadcrumb-seting-bar-flex mt15">
          <div class="layout-breadcrumb-seting-bar-flex-label">{{ $t('message.layout.fourIsGrayscale') }}</div>
          <div class="layout-breadcrumb-seting-bar-flex-value">
            <el-switch v-model="getThemeConfig.isGrayscale" :width="35" @change="onAddFilterChange('grayscale')">
            </el-switch>
          </div>
        </div>
        <div class="layout-breadcrumb-seting-bar-flex mt15">
          <div class="layout-breadcrumb-seting-bar-flex-label">{{ $t('message.layout.fourIsInvert') }}</div>
          <div class="layout-breadcrumb-seting-bar-flex-value">
            <el-switch v-model="getThemeConfig.isInvert" :width="35" @change="onAddFilterChange('invert')"> </el-switch>
          </div>
        </div>
        <!-- chế độ tối -->
        <!-- <div class="layout-breadcrumb-seting-bar-flex mt15">
          <div class="layout-breadcrumb-seting-bar-flex-label">{{ $t('message.layout.fourIsDark') }}</div>
          <div class="layout-breadcrumb-seting-bar-flex-value">
            <el-switch v-model="getThemeConfig.isIsDark" :width="35" @change="onAddDarkChange"> </el-switch>
          </div>
        </div> -->
        <!-- Các cài đặt khác -->
        <el-divider :content-position="contentPosotion">{{ $t('message.layout.fiveTitle') }}</el-divider>
        <div class="layout-breadcrumb-seting-bar-flex mt15">
          <div class="layout-breadcrumb-seting-bar-flex-label">{{ $t('message.layout.fiveTagsStyle') }}</div>
          <div class="layout-breadcrumb-seting-bar-flex-value">
            <el-radio-group
              v-model="getThemeConfig.tagsStyle"
              :disabled="!getThemeConfig.isTagsview"
              size="mini"
              @change="setLocalThemeConfig"
            >
              <el-radio-button label="tags-style-one">thẻ</el-radio-button>
              <el-radio-button label="tags-style-four">Thông minh</el-radio-button>
              <el-radio-button label="tags-style-five">trơn</el-radio-button>
            </el-radio-group>
          </div>
        </div>
        <div class="layout-breadcrumb-seting-bar-flex mt15">
          <div class="layout-breadcrumb-seting-bar-flex-label">{{ $t('message.layout.fiveAnimation') }}</div>
          <div class="layout-breadcrumb-seting-bar-flex-value">
            <el-radio-group v-model="getThemeConfig.animation" size="mini" @input="setLocalThemeConfig">
              <el-radio-button label="slide-left">Vuốt sang trái</el-radio-button>
              <el-radio-button label="opacitys">trong suốt</el-radio-button>
              <el-radio-button label="slide-right">Vuốt sang phải</el-radio-button>
              <el-radio-button label="no-transition">không có</el-radio-button>
            </el-radio-group>
          </div>
        </div>
        <div
          class="layout-breadcrumb-seting-bar-flex mt15"
          :class="{ mb28: getThemeConfig.layout !== 'columns' && getThemeConfig.layout !== 'classic' }"
        >
          <div class="layout-breadcrumb-seting-bar-flex-label">{{ $t('message.layout.fiveColumnsAsideStyle') }}</div>
          <div class="layout-breadcrumb-seting-bar-flex-value">
            <el-radio-group v-model="getThemeConfig.columnsAsideStyle" size="mini" @input="setLocalThemeConfig">
              <el-radio-button label="columns-round">góc tròn</el-radio-button>
              <el-radio-button label="columns-card">thẻ</el-radio-button>
            </el-radio-group>
          </div>
        </div>
        <div
          class="layout-breadcrumb-seting-bar-flex mt15 mb28"
          v-if="getThemeConfig.layout === 'columns' || getThemeConfig.layout === 'classic'"
        >
          <div class="layout-breadcrumb-seting-bar-flex-label">{{ $t('message.layout.fiveColumnsAsideLayout') }}</div>
          <div class="layout-breadcrumb-seting-bar-flex-value">
            <el-radio-group v-model="getThemeConfig.columnsAsideLayout" size="mini" @input="setLocalThemeConfig">
              <el-radio-button label="columns-horizontal">mức độ</el-radio-button>
              <el-radio-button label="columns-vertical">thẳng đứng</el-radio-button>
            </el-radio-group>
          </div>
        </div>
      </el-scrollbar>
    </el-drawer>
  </div>
</template>

<script>
import ClipboardJS from 'clipboard';
import { Local } from '@/utils/storage.js';
import { useChangeColor } from '@/utils/theme.js';
import config from '../../../../package.json';
import { themeList } from './theme';
export default {
  name: 'layoutBreadcrumbSeting',
  computed: {
    // Nhận thông tin cấu hình bố cục
    getThemeConfig() {
      return this.$store.state.themeConfig.themeConfig;
    },
  },
  data() {
    return {
      contentPosotion: 'center',
    };
  },
  created() {
    // Xác định xem bố cục hiện tại có khác không. Nếu không, hãy khởi tạo kiểu bố cục hiện tại để ngăn chặn logo cấu hình bố cục, nền menu và các lỗi bố cục một phần khác khi kích thước của cửa sổ giám sát thay đổi.
    Local.set('frequency', 1);
    // Giám sát các thay đổi kích thước cửa sổ, bố cục không mặc định, đặt thành bố cục mặc định (thích ứng với thiết bị đầu cuối di động)）
    this.bus.$on('layoutMobileResize', (res) => {
      if (this.$store.state.themeConfig.themeConfig.layout === res.layout) return false;
      this.$store.state.themeConfig.themeConfig.layout = res.layout;
      this.$store.state.themeConfig.themeConfig.isDrawer = false;
      this.$store.state.themeConfig.themeConfig.isCollapse = false;
    });
    this.setLocalTheme(this.$store.state.themeConfig.themeConfig.themeStyle);
  },
  mounted() {
    this.initLayoutConfig();
  },
  methods: {
    // chủ đề toàn cầu
    onColorPickerChange() {
      // if (!this.getThemeConfig.primary) return;
      // Màu sắc đậm hơn
      // document.documentElement.style.setProperty('--prev-color-primary', this.getThemeConfig.primary);
      // làm sáng màu
      for (let i = 1; i <= 9; i++) {
        document.documentElement.style.setProperty(
          `--prev-color-primary-light-${i}`,
          `${useChangeColor().getLightColor(this.getThemeConfig.primary, i / 10)}`,
        );
      }
      this.setLocalThemeConfig();
    },
    setLocalTheme(val) {
      let themeSelect = themeList[val];
      themeSelect['--prev-border-color-lighter'] = '#ebeef5';
      /**
       * Đặt kiểu dựa trên cấu hình chủ đề
       * @param {string} val - giá trị chủ đề
       */
      if (['classic'].includes(this.getThemeConfig.layout)) {
        // Bố cục thứ ba
        themeSelect['--prev-bg-topBar'] = '#282c34';
        themeSelect['--prev-bg-topBarColor'] = '#fff';
        // themeSelect['--prev-MenuActiveColor'] = '#fff';
        themeSelect['--prev-bg-menuBarColor'] = '#515a6e';
        themeSelect['--prev-bg-menu-hover-ba-color'] = '#e5eeff';
        // themeSelect['--prev-MenuActiveColor'] = '#6954f0';
        if (val == 'theme-1') {
          themeSelect['--prev-bg-menuBar'] = '#fff';
          themeSelect['--prev-border-color-lighter'] = '#e5eeff';
          themeSelect['--prev-MenuActiveColor'] = '#0256FF';
        } else if (val == 'theme-3') {
          // themeSelect['--prev-bg-menu-hover-ba-color'] = '#41b584';
          themeSelect['--prev-bg-menuBar'] = '#fff';
          themeSelect['--prev-border-color-lighter'] = '#282c34';
          themeSelect['--prev-MenuActiveColor'] = '#41b584';
        } else if (val == 'theme-5') {
          // themeSelect['--prev-bg-menu-hover-ba-color'] = '#6954f0';
          themeSelect['--prev-bg-menuBar'] = '#fff';
          themeSelect['--prev-border-color-lighter'] = '#282c34';
          themeSelect['--prev-MenuActiveColor'] = '#6954f0';
        } else if (val == 'theme-7') {
          // themeSelect['--prev-bg-menu-hover-ba-color'] = '#f34d37';
          themeSelect['--prev-bg-menuBar'] = '#fff';
          themeSelect['--prev-border-color-lighter'] = '#282c34';
          themeSelect['--prev-MenuActiveColor'] = '#f34d37';
        } else {
          themeSelect['--prev-border-color-lighter'] = '#ebeef5';
          themeSelect['--prev-bg-topBar'] = '#fff';
          themeSelect['--prev-bg-topBarColor'] = '#515a6e';
          themeSelect['--prev-bg-columnsMenuActiveColor'] = '#515a6e';

          // themeSelect['--prev-bg-menuBarColor'] = '#515a6e';
        }
      } else if (['transverse'].includes(this.getThemeConfig.layout)) {
        // Bố cục thứ tư
        themeSelect['--prev-bg-topBar'] = '#282c34';
        themeSelect['--prev-bg-topBarColor'] = '#fff';
        themeSelect['--prev-bg-menuBarColor'] = '#fff';
        themeSelect['--prev-MenuActiveColor'] = '#fff';
        if (val == 'theme-1') {
          themeSelect['--prev-bg-menu-hover-ba-color'] = '#0256FF';
          themeSelect['--prev-bg-menuBar'] = '#282c34';
          themeSelect['--prev-border-color-lighter'] = '#282c34';
        } else if (val == 'theme-3') {
          themeSelect['--prev-bg-menu-hover-ba-color'] = '#41b584';
          themeSelect['--prev-bg-menuBar'] = '#282c34';
          themeSelect['--prev-border-color-lighter'] = '#282c34';
        } else if (val == 'theme-5') {
          themeSelect['--prev-bg-menu-hover-ba-color'] = '#6954f0';
          themeSelect['--prev-bg-menuBar'] = '#282c34';
          themeSelect['--prev-border-color-lighter'] = '#282c34';
        } else if (val == 'theme-7') {
          themeSelect['--prev-bg-menu-hover-ba-color'] = '#f34d37';
          themeSelect['--prev-bg-menuBar'] = '#282c34';
          themeSelect['--prev-border-color-lighter'] = '#282c34';
        } else {
          themeSelect['--prev-border-color-lighter'] = '#ebeef5';

          themeSelect['--prev-bg-topBar'] = '#fff';
          themeSelect['--prev-bg-topBarColor'] = '#515a6e';
          themeSelect['--prev-bg-menuBarColor'] = '#515a6e';
          themeSelect['--prev-MenuActiveColor'] = '#515a6e';
        }
      } else if (this.getThemeConfig.layout === 'columns') {
        //Bố cục thứ hai
        themeSelect['--prev-bg-topBar'] = '#fff';
        themeSelect['--prev-bg-topBarColor'] = '#515a6e';
        themeSelect['--prev-bg-menuBar'] = '#fff';
        themeSelect['--prev-bg-menuBarColor'] = '#303133';
        themeSelect['--prev-border-color-lighter'] = '#ebeef5';
        if (val == 'theme-1') {
          themeSelect['--prev-bg-menu-hover-ba-color'] = '#e5eeff';
          themeSelect['--prev-color-primary'] = '#0256FF';
          themeSelect['--prev-MenuActiveColor'] = '#0256FF';
        } else if (val == 'theme-3') {
          themeSelect['--prev-bg-menu-hover-ba-color'] = '#ecf8f3';
          themeSelect['--prev-color-primary'] = '#41b584';
          themeSelect['--prev-MenuActiveColor'] = '#41b584';
        } else if (val == 'theme-5') {
          themeSelect['--prev-bg-menu-hover-ba-color'] = '#f0eefe';
          themeSelect['--prev-color-primary'] = '#6954f0';
          themeSelect['--prev-MenuActiveColor'] = '#6954f0';
        } else if (val == 'theme-7') {
          themeSelect['--prev-bg-menu-hover-ba-color'] = '#feedeb';
          themeSelect['--prev-color-primary'] = '#f34d37';
          themeSelect['--prev-MenuActiveColor'] = '#f34d37';
        }
      } else {
        //bố cục mặc định
        if (val == 'theme-1') {
          themeSelect['--prev-bg-menuBar'] = '#282c34';
          themeSelect['--prev-color-primary'] = '#0256FF';
          themeSelect['--prev-bg-topBarColor'] = '#282c34';
          themeSelect['--prev-bg-topBar'] = '#fff';
          themeSelect['--prev-bg-menuBarColor'] = '#fff';
          themeSelect['--prev-MenuActiveColor'] = '#fff';
          themeSelect['--prev-bg-menu-hover-ba-color'] = '#0256FF';
        } else if (val == 'theme-3') {
          themeSelect['--prev-bg-menuBar'] = '#282c34';
          themeSelect['--prev-color-primary'] = '#41b584';
          themeSelect['--prev-bg-topBar'] = '#fff';
          themeSelect['--prev-bg-topBarColor'] = '#282c34';
          themeSelect['--prev-bg-menuBarColor'] = '#fff';
          themeSelect['--prev-MenuActiveColor'] = '#fff';
          themeSelect['--prev-bg-menu-hover-ba-color'] = '#41b584';
        } else if (val == 'theme-5') {
          themeSelect['--prev-bg-menuBar'] = '#282c34';
          themeSelect['--prev-bg-topBarColor'] = '#282c34';
          themeSelect['--prev-color-primary'] = '#6954f0';
          themeSelect['--prev-bg-topBar'] = '#fff';
          themeSelect['--prev-bg-menuBarColor'] = '#fff';
          themeSelect['--prev-MenuActiveColor'] = '#fff';
          themeSelect['--prev-bg-menu-hover-ba-color'] = '#6954f0';
        } else if (val == 'theme-7') {
          themeSelect['--prev-bg-menuBar'] = '#282c34';
          themeSelect['--prev-bg-topBar'] = '#fff';
          themeSelect['--prev-bg-topBarColor'] = '#282c34';
          themeSelect['--prev-color-primary'] = '#f34d37';
          themeSelect['--prev-bg-menuBarColor'] = '#fff';
          themeSelect['--prev-MenuActiveColor'] = '#fff';
          themeSelect['--prev-bg-menu-hover-ba-color'] = '#f34d37';
        }
      }

      if (['theme-1', 'theme-2'].includes(val)) {
        this.$store.state.themeConfig.themeConfig.primary = '#0256FF'; //xanh đen xanh trắng
      } else if (['theme-3', 'theme-4'].includes(val)) {
        this.$store.state.themeConfig.themeConfig.primary = '#41a584'; //xanh đen xanh trắng
      } else if (['theme-5', 'theme-6'].includes(val)) {
        this.$store.state.themeConfig.themeConfig.primary = '#6954f0'; //tím đen tím trắng
      } else if (['theme-7', 'theme-8'].includes(val)) {
        this.$store.state.themeConfig.themeConfig.primary = '#f34d37'; //đỏ đen đỏ trắng
      } else {
        this.$store.state.themeConfig.themeConfig.primary = '#0256FF'; //Màu xanh mặc định
      }
      /**
       * Duyệt qua đối tượng chọn chủ đề và đặt giá trị thuộc tính của nó thành thuộc tính style của phần tử gốc tài liệu
       */
      for (let key in themeSelect) {
        // Sử dụng các thuộc tính của đối tượng chọn chủ đề làm tên thuộc tính kiểu và giá trị thuộc tính làm giá trị thuộc tính kiểu và đặt chúng thành phần tử gốc của tài liệu.
        document.documentElement.style.setProperty(key, themeSelect[key]);
      }
      // Thực thi chức năng gọi lại sau chu kỳ cập nhật DOM tiếp theo
      this.$nextTick((e) => {
        // Gọi phương thức onColorPickerChange
        this.onColorPickerChange();
      });
    },
    onMenuBgColorChange() {
      if (!this.getThemeConfig.menuBgColor) return;
      // Màu sắc đậm hơn
      document.documentElement.style.setProperty('--prev-bg-menuBar', this.getThemeConfig.menuBgColor);
      this.setLocalThemeConfig();
    },
    // chế độ tối
    onAddDarkChange() {
      const body = document.documentElement;
      if (this.getThemeConfig.isIsDark) body.setAttribute('data-theme', 'dark');
      else body.setAttribute('data-theme', '');
      this.setLocalThemeConfig();
    },
    // Khởi tạo: Khi làm mới trang, giá trị được đặt và giá trị trong bộ đệm được lấy trực tiếp để khởi tạo.
    initLayoutConfig() {
      window.addEventListener('load', () => {
        // Kiểu mặc định
        this.onColorPickerChange();
        // chế độ màu xám
        if (this.$store.state.themeConfig.themeConfig.isGrayscale) this.onAddFilterChange('grayscale');
        // Chế độ yếu màu
        if (this.$store.state.themeConfig.themeConfig.isInvert) this.onAddFilterChange('invert');
        // chế độ tối
        if (this.$store.state.themeConfig.themeConfig.isIsDark) this.onAddDarkChange();
        // quốc tế hóa ngôn ngữ
        if (Local.get('themeConfigPrev')) this.$i18n.locale = Local.get('themeConfigPrev').globalI18n;
      });
    },
    // Cấu hình bố trí cửa hàng
    setLocalThemeConfig() {
      Local.remove('themeConfigPrev');
      Local.set('themeConfigPrev', this.$store.state.themeConfig.themeConfig);
      this.setLocalThemeConfigStyle();
    },
    // Cấu hình bố cục cửa hàng kiểu chủ đề toàn cầu (thẻ gốc html）
    setLocalThemeConfigStyle() {
      Local.set('themeConfigStyle', document.documentElement.style.cssText);
    },
    // Cửa sổ bật lên cấu hình bố cục sẽ mở ra.
    openDrawer() {
      this.$store.state.themeConfig.themeConfig.isDrawer = true;
    },
    // Khi đóng cửa sổ bật lên, hãy khởi tạo các biến
    onDrawerClose() {
      this.$store.state.themeConfig.themeConfig.isDrawer = false;
      this.setLocalThemeConfig();
    },
    // Chế độ màu xám/chế độ màu yếu
    onAddFilterChange(attr) {
      if (attr === 'grayscale') {
        if (this.$store.state.themeConfig.themeConfig.isGrayscale)
          this.$store.state.themeConfig.themeConfig.isInvert = false;
      } else {
        if (this.$store.state.themeConfig.themeConfig.isInvert)
          this.$store.state.themeConfig.themeConfig.isGrayscale = false;
      }
      const cssAttr =
        attr === 'grayscale'
          ? `grayscale(${this.$store.state.themeConfig.themeConfig.isGrayscale ? 1 : 0})`
          : `invert(${this.$store.state.themeConfig.themeConfig.isInvert ? '80%' : '0%'})`;
      const appEle = document.body;
      appEle.setAttribute('style', `filter: ${cssAttr};`);
      this.setLocalThemeConfig();
    },
    // Chuyển đổi bố cục
    onSetLayout(layout) {
      Local.set('oldLayout', layout);
      if (this.$store.state.themeConfig.themeConfig.layout === layout) return false;
      if (['classic', 'transverse'].includes(layout)) {
        this.$store.state.themeConfig.themeConfig.isTagsview = false;
      } else {
        this.$store.state.themeConfig.themeConfig.isTagsview = true;
      }
      this.$store.state.themeConfig.themeConfig.layout = layout;
      this.$store.state.themeConfig.themeConfig.isDrawer = false;
      this.$store.state.themeConfig.themeConfig.columnsAsideStyle = 'columns-card';
      this.setLocalTheme(this.$store.state.themeConfig.themeConfig.themeStyle);
    },
    // Nền menu/thanh trên cùng, v.v.
    onBgColorPickerChange(bg, rgb) {
      document.documentElement.style.setProperty(`--prev-bg-${bg}`, rgb);
      this.setLocalThemeConfigStyle();
    },
    // Cấu hình sao chép bằng một cú nhấp chuột
    onCopyConfigClick() {
      this.$store.state.themeConfig.themeConfig.isDrawer = false;
      let clipboardJS = new ClipboardJS('.copy-config-btn', {
        text: () => JSON.stringify(this.$store.state.themeConfig.themeConfig),
      });
      clipboardJS.on('success', () => {
        this.$message.success('Sao chép cấu hình thành công');
        this.isDrawer = false;
        clipboardJS.destroy();
      });
      clipboardJS.on('error', () => {
        this.$message.error('Sao chép cấu hình không thành công');
      });
    },
    // Khôi phục mặc định bằng một cú nhấp chuột
    onResetConfigClick() {
      Local.clear();
      window.location.reload();
      Local.set('version', config.version);
    },
  },
};
</script>
<style>
body .v-modal {
  background-color: rgba(0, 0, 0, 0.1);
}
</style>
<style scoped lang="scss">
.w10 {
  width: 10px;
}
.mr5 {
  margin-right: 5px;
}
::v-deep .el-drawer__header {
  margin-bottom: 0;
}
::v-deep .el-radio-button--mini .el-radio-button__inner {
  padding: 7px 8px;
}
::v-deep .el-drawer__body {
  padding: 0;
}
.layout-breadcrumb-seting-bar {
  // height: calc(100vh - 50px);
  padding: 0 15px;
  ::v-deep .el-scrollbar__view {
    // overflow-x: auto !important;
    overflow-x: hidden;
  }
  .layout-breadcrumb-seting-bar-flex {
    display: flex;
    align-items: center;
    &-label {
      flex: 1;
      color: var(--prev-color-text-primary);
    }
  }
  .layout-drawer-content-flex {
    overflow: hidden;
    display: flex;
    flex-wrap: wrap;
    align-content: center;
    justify-content: center;
    margin: 0 -5px;
    .layout-drawer-content-item.drawer-layout-active {
      border: 1px solid;
      border-color: var(--prev-color-primary);
    }
    .layout-drawer-content-item:hover {
      transition: all 0.3s ease-in-out;
      border: 1px solid;
      border-color: var(--prev-color-primary);
    }
    .layout-drawer-content-item {
      width: 107px;
      height: 70px;
      cursor: pointer;
      border: 1px solid rgba(0, 0, 0, 0);
      position: relative;
      padding: 6px;
      background: #ffffff;
      box-shadow: 0px 2px 5px 0px rgba(0, 0, 0, 0.08);
      border-radius: 6px;
      opacity: 1;
      margin: 10px;

      .el-container {
        height: 100%;
        .el-aside-dark {
          opacity: 0.5;
          background-color: var(--prev-tag-active-color);
          border-radius: 2px;
        }
        .el-aside {
          background-color: var(--prev-tag-active-color);
          border-radius: 2px;
        }
        .el-header {
          border-radius: 2px;
          background-color: var(--prev-color-seting-header);
        }
        .el-main {
          border-radius: 2px;
          border: 1px dashed var(--prev-color-primary);
          padding: 0;
          background-color: var(--prev-color-seting-main);
        }
      }
      .el-circular {
        border-radius: 2px;
        overflow: hidden;
        border: 1px solid transparent;
        transition: all 0.3s ease-in-out;
      }

      .layout-tips-warp,
      .layout-tips-warp-active {
        transition: all 0.3s ease-in-out;
        position: absolute;
        left: 50%;
        top: 50%;
        transform: translate(-50%, -50%);
        border: 1px solid;
        border-color: var(--prev-color-primary-light-5);
        border-radius: 100%;
        padding: 4px;
        .layout-tips-box {
          transition: inherit;
          width: 30px;
          height: 30px;
          z-index: 9;
          border: 1px solid;
          border-color: var(--prev-color-primary-light-5);
          border-radius: 100%;
          .layout-tips-txt {
            transition: inherit;
            position: relative;
            top: 5px;
            font-size: 12px;
            line-height: 1;
            letter-spacing: 2px;
            white-space: nowrap;
            color: var(--prev-color-primary-light-5);
            text-align: center;
            transform: rotate(30deg);
            left: -1px;
            background-color: var(--prev-color-seting-main);
            width: 32px;
            height: 17px;
            line-height: 17px;
          }
        }
      }
      .layout-tips-warp-active {
        border: 1px solid;
        border-color: var(--prev-color-primary);
        .layout-tips-box {
          border: 1px solid;
          border-color: var(--prev-color-primary);
          .layout-tips-txt {
            color: var(--prev-color-primary) !important;
            background-color: var(--prev-color-seting-main) !important;
          }
        }
      }
      &:hover {
        .layout-tips-warp {
          transition: all 0.3s ease-in-out;
          border-color: var(--prev-color-primary);
          .layout-tips-box {
            transition: inherit;
            border-color: var(--prev-color-primary);
            .layout-tips-txt {
              transition: inherit;
              color: var(--prev-color-primary) !important;
              background-color: var(--prev-color-seting-main) !important;
            }
          }
        }
      }
    }
  }
  .copy-config {
    margin: 10px 0;
    .copy-config-btn {
      width: 100%;
      margin-top: 15px;
    }
    .copy-config-btn-reset {
      width: 100%;
      margin: 10px 0 0;
    }
  }
}
</style>
