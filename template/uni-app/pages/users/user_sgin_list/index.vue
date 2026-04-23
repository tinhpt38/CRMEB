<template>
	<view>
		<view class='sign-record'>
		   <view class='list' v-for="(item,index) in signList" :key="index">
		      <view class='item'>
		         <view class='data'>{{item.month}}</view>
		         <view class='listn'>
		            <view class='itemn acea-row row-between-wrapper' v-for="(itemn,indexn) in item.list" :key="indexn">
		               <view>
		                  <view class='name line1'>{{$t(itemn.title)}}</view>
		                  <view>{{itemn.add_time}}</view>
		               </view>
		               <view class='num'>+{{itemn.number}}</view>
		            </view>
		         </view>
		      </view>
		   </view>
		    <view class='loadingicon acea-row row-center-wrapper' v-if="signList.length > 0">
		        <text class='loading iconfont icon-jiazai' :hidden='loading==false'></text>{{loadtitle}}
		    </view>
			<view v-if="signList.length == 0"><emptyPage :title="$t(`Chưa có hồ sơ đăng ký~`)"></emptyPage></view>
		</view>
		<!-- #ifdef MP -->
		<!-- <authorize @onLoadFun="onLoadFun" :isAuto="isAuto" :isShowAuth="isShowAuth" @authColse="authColse"></authorize> -->
		<!-- #endif -->
	</view>
</template>

<script>
	import { getSignMonthList } from '@/api/user.js';
	import { toLogin } from '@/libs/login.js';
	 import { mapGetters } from "vuex";
	 import emptyPage from '@/components/emptyPage';
	 // #ifdef MP
	 import authorize from '@/components/Authorize';
	 // #endif
	export default {
		components: {
			emptyPage,
			// #ifdef MP
			authorize
			// #endif
		},
		data() {
			return {
				loading:false,
				    loadend:false,
				    loadtitle:this.$t(`tải thêm`),
				    page:1,
				    limit:8,
				    signList:[],
					isAuto: false, //Nếu không có ủy quyền, nó sẽ không được ủy quyền tự động.
					isShowAuth: false //Có ẩn ủy quyền hay không
			};
		},
		computed: mapGetters(['isLogin']),
		watch:{
			isLogin:{
				handler:function(newV,oldV){
					if(newV){
						this.getSignMoneList();
					}
				},
				deep:true
			}
		},
		onLoad(){
			if(this.isLogin){
				this.getSignMoneList();
			}else{
				toLogin();
			}
		},
		onReachBottom: function () {
		    this.getSignMoneList();
		  },
		methods: {
			  /**
			   * 
			   * Gọi lại ủy quyền
			  */
			  onLoadFun:function(){
			    this.getSignMoneList();
			  },
			  // Ủy quyền đã đóng
			  authColse:function(e){
			  	this.isShowAuth = e
			  },
			  /**
			     * Lấy danh sách hồ sơ đăng ký
			    */
			    getSignMoneList:function(){
			      let that=this;
			      if(that.loading) return;
			      if(that.loadend) return;
				  that.loading = true;
				  that.loadtitle = "";
			      getSignMonthList({ page: that.page, limit: that.limit }).then(res=>{
			        let list = res.data;
			        let loadend = list.length < that.limit;
			        that.signList = that.$util.SplitArray(list, that.signList);
					that.$set(that,'signList',that.signList);
					that.loadend = loadend;
					that.loading = false;
					that.loadtitle = loadend ? that.$t(`Tôi cũng có một điểm mấu chốt`) : that.$t(`tải thêm`);
			      }).catch(err=>{
					that.loading = false;
					that.loadtitle = that.$t(`tải thêm`);
			      });
			    },
		}
	}
</script>

<style>
.sign-record .num{
	color: #fe5c2d!important;
}
</style>
