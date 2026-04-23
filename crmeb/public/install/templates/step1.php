<!doctype html>
<html>
<head>
<meta charset="UTF-8" />
<title><?php echo $Title; ?> - <?php echo $Powered; ?></title>
<link rel="stylesheet" href="./css/install.css?v=9.0" />
<link rel="stylesheet" href="./css/step1.css?v=9.0" />
<link rel="stylesheet" href="./css/theme-chalk.css">
    <script src="./js/vue2.6.11.js"></script>
<script src="./js/element-ui.js?v=9.0"></script>
</head>
<body>
<div class="wrap" id="step1">
<!--  --><?php //require './templates/header.php';?>
  <div class="title">
      <img class="logo" src="./images/install/logo-step1.png" alt="">
      <h1>Chào mừng đến với Phiên bản tiêu chuẩn CRMEB</h1>
      <div class="df agreement cp">
          <div class="radio-box" :class="{'is-shock': isShock}" @click="radio = !radio">
              <img v-if="radio" src="./images/install/success.png" alt="">
          </div>
          <span @click="radio = !radio">Đọc chi tiết và đánh dấu vào ô đồng ý</span>
          <span class="agreements" @click.stop="isShow = 1">《Thỏa thuận sử dụng phần mềm》</span>
      </div>
      <div class="bottom tac"> <span class="btn" :class="{'more-text': radio}" @click="jump">
              Bắt đầu cài đặt</span> </div>
      <img class="solgen" src="./images/install/solgen.png" alt="">
  </div>
  <div class="section" v-if="isShow">
      <div class="main cc">
          <pre class="pact" readonly="readonly">
          <h1 class="title">Thỏa thuận cấp phép phần mềm</h1>
Điều khoản nhắc nhở：
    <strong>Thỏa thuận này được ký kết giữa bạn và Công ty TNHH Công nghệ Mạng Xi'an Zhongbang.。</strong>
    CRMEBHệ thống quản lý khách hàng + thương mại điện tử (sau đây gọi tắt là“CRMEB”），Bởi Công ty TNHH Công nghệ Mạng Xi'an Zhongbang (sau đây gọi là“Công nghệ Trung Bang”）Phát triển ban đầu, mọi quyền được bảo lưuCopyright (c)2014-2026，Mọi quyền được bảo lưu bởi Zhongbang Technology. CRMEB là một trong những giải pháp nền tảng thương mại điện tử Internet ổn định, mạnh mẽ và tiên tiến nhất tại Trung Quốc. CRMEB dựa trên công nghệ PHP + MySQL và được phát triển bằng khung ThinkPHP. CRMEB chính thức có quyền sửa đổi và giải thích cuối cùng.
    Khi sử dụng hệ thống quản lý khách hàng + thương mại điện tử CRMEB (sau đây gọi tắt là“Phần mềm được cấp phép”hoặc“Phần mềm này”）Trước khi tiếp tục, vui lòng đọc kỹ Thỏa thuận này, đặc biệt là các điều khoản pháp lý và giải quyết tranh chấp hiện hành. Những điều khoản này sẽ được đánh dấu in đậm và bạn cần đọc kỹ. Nếu bạn có bất kỳ câu hỏi nào về thỏa thuận, bạn có thể tham khảo dịch vụ khách hàng. Nếu bạn đã tải xuống, sao chép, cài đặt hoặc sử dụng phần mềm theo cách khác, bạn được coi là đã chấp nhận Thỏa thuận này. Nếu bạn không chấp nhận tất cả hoặc một phần các điều khoản của Thỏa thuận này, bạn không được phép sử dụng Phần mềm. Vui lòng chấm dứt ngay việc cài đặt hoặc sử dụng phần mềm khác và xóa mọi thành phần của phần mềm mà bạn đã cài đặt hoặc giữ lại.
Do sự phát triển nhanh chóng của Internet, các điều khoản được liệt kê trong thỏa thuận này do bạn và chúng tôi ký kết không thể liệt kê và bao hàm đầy đủ tất cả các quyền và nghĩa vụ của bạn và chúng tôi, đồng thời thỏa thuận hiện tại không thể đảm bảo rằng nó sẽ đáp ứng đầy đủ nhu cầu phát triển trong tương lai.
    Do đó, Tuyên bố về Bản quyền và các quy định khác là các thỏa thuận bổ sung cho Thỏa thuận này, không thể tách rời khỏi Thỏa thuận này và có hiệu lực pháp lý như nhau. Nếu bạn sử dụng phần mềm được cấp phép, bạn được coi là đã đồng ý với thỏa thuận bổ sung ở trên. Nếu chúng tôi sửa đổi thỏa thuận này hoặc thỏa thuận bổ sung của nó, sau khi các điều khoản của thỏa thuận được sửa đổi, vui lòng đọc và chấp nhận thỏa thuận đã sửa đổi một cách cẩn thận trước khi tiếp tục sử dụng phần mềm được cấp phép.。
<br/>
1. Định nghĩa
    PHẦN MỀM (PHẦN MỀM ĐƯỢC CẤP PHÉP HOẶC PHẦN MỀM): TRONG THỎA THUẬN NÀY“phần mềm”Nó đề cập đến hệ thống thương mại điện tử + quản lý khách hàng CRMEB, bao gồm một số mô-đun hoặc chức năng và là một chương trình xử lý thông tin hoặc tệp hỗ trợ đã được cấy ghép hoặc sẽ được cấy vào các sản phẩm được chỉ định của Công nghệ Zhongbang. Các tệp hỗ trợ cụ thể bao gồm mã nguồn và mã đối tượng của phần mềm cũng như các hình ảnh và hình ảnh có trong phần mềm liên quan. Tất cả hoặc một phần ảnh, biểu tượng, hoạt ảnh, bản ghi âm, video, nhạc, văn bản, mã và tất cả tài liệu giấy hoặc điện tử, tài liệu kỹ thuật, v.v. liên quan đến phần mềm được cấp phép hoặc các sản phẩm của Zhongbang mô tả chức năng, tính năng, nội dung, chất lượng, thử nghiệm, hướng dẫn sử dụng, thỏa thuận cấp phép người dùng, v.v.
    Bạn: trong Thỏa thuận này“Bạn”Đề cập đến một cá nhân hoặc một pháp nhân duy nhất đã được Zhongbang Technology cho phép hợp pháp để sử dụng phần mềm này. Pháp nhân bao gồm công ty, doanh nghiệp, cơ quan, tổ chức, đơn vị.
    Chúng tôi: Trong Thỏa thuận này“chúng ta”Đó là CRMEB chính thức, dùng để chỉ Công nghệ Zhongbang, tức là Công ty TNHH Công nghệ Mạng Xi'an Zhongbang và các công ty liên kết của nó.
    Phát triển thứ cấp: trong thỏa thuận này“Phát triển thứ cấp”Về nguyên tắc, việc tùy chỉnh và sửa đổi phần mềm hiện có, chẳng hạn như mở rộng các chức năng để đạt được các chức năng bạn muốn, không được thay đổi lõi hệ thống ban đầu và cài đặt hệ thống của phần mềm. Sự phát triển thứ cấp mà chúng tôi cho phép chỉ đề cập đến việc xóa, sửa đổi hoặc mở rộng một số giao diện và chức năng phần mềm và không thực hiện các sửa đổi đáng kể đối với lõi và khung.。
<br/>
2. Nội dung giấy phép phần mềm
Với cơ sở là bạn tuân thủ nội dung của thỏa thuận này, sau khi bạn mua giấy phép thương mại phần mềm thông qua các kênh hợp pháp được chỉ định của chúng tôi, các quyền trong giấy phép thương mại do Zhongbang Technology cấp cho bạn bao gồm:
1. Quyền cài đặt và sử dụng: Bạn có thể cài đặt và sử dụng phần mềm này cho mục đích thương mại và sử dụng tất cả các chức năng do phần mềm này cung cấp.
2. Quyền ràng buộc một tên miền duy nhất: Trước khi cài đặt phần mềm này, bạn nên chuẩn bị một tên miền và thông báo cho chúng tôi để chúng tôi có thể ràng buộc tên miền với phần mềm này. Tên miền bị ràng buộc là liên kết duy nhất tới phần mềm được cấp phép thương mại. Bạn nên đảm bảo tính duy nhất và hợp lệ của tên miền. Tên miền đã bị ràng buộc thì không thể tùy ý thay đổi được. Tên miền bạn chuẩn bị có thể là tên miền cấp cao nhất, cấp hai hoặc cấp ba. Bạn phải chịu trách nhiệm về tính hợp pháp và hiệu lực của tên miền. Trong quá trình sử dụng phần mềm này, nếu bạn cần thay đổi tên miền, bạn nên thông báo bằng văn bản cho chúng tôi trước ba ngày làm việc và thông báo trung thực cho chúng tôi về các vấn đề xảy ra với tên miền đã thay đổi. Nếu không, chúng tôi có quyền không thay đổi nó.
3. Quyền đăng ký mã ủy quyền thương mại: Sau khi mua giấy phép thương mại phần mềm thông qua các kênh hợp pháp được chỉ định của chúng tôi, bạn có thể đăng ký mã ủy quyền thương mại trên trang web chính thức của chúng tôi cùng với số đơn đặt hàng của bạn và tải xuống chứng chỉ ủy quyền thông qua trang web chính thức của chúng tôi.
4. Quyền nhận được chứng chỉ ủy quyền thương mại: Chứng chỉ ủy quyền thương mại được tải xuống thông qua trang web chính thức của chúng tôi là chứng chỉ hợp pháp cho phép bạn sử dụng phần mềm vì mục đích thương mại. Chứng chỉ ủy quyền này cấp cho bạn ủy quyền vĩnh viễn để sử dụng hợp pháp phần mềm theo cách thức được quy định trong thỏa thuận này, nhưng chúng tôi không đưa ra cam kết lâu dài về việc sử dụng ủy quyền không giới hạn.
5. Quyền sử dụng nội dung được ủy quyền: Sau khi được chúng tôi cho phép, bạn có quyền sử dụng tất cả nội dung của trang web được xây dựng bằng phần mềm này và bạn phải độc lập chịu các nghĩa vụ pháp lý liên quan. Bạn có thể sửa đổi mã nguồn hoặc kiểu giao diện CRMEB cho phù hợp với yêu cầu trang web của mình trong các ràng buộc và giới hạn được quy định trong thỏa thuận, nhưng thông tin bản quyền của chúng tôi phải được giữ lại. Bất kể trang web của bạn sử dụng CRMEB nói chung hay một số cột sử dụng CRMEB, trang web chính thức của CRMEB phải được thêm vào trang chủ của trang web nơi bạn sử dụng CRMEB.(www.CRMEB.com)liên kết.
6. Chỉ sau khi có được ủy quyền thương mại, bạn mới có thể sử dụng phần mềm này cho mục đích thương mại và nội dung hỗ trợ kỹ thuật sẽ được xác định dựa trên loại ủy quyền đã mua. Người dùng được ủy quyền thương mại có quyền đưa ra phản hồi và đưa ra đề xuất. Những nhận xét và đề xuất có liên quan sẽ được ưu tiên trong lần nâng cấp phần mềm tiếp theo của chúng tôi, nhưng chúng tôi không đưa ra cam kết hay đảm bảo nào về vấn đề này.
7. Bản quyền CRMEB đã được đăng ký với Cục Bản quyền Quốc gia Cộng hòa Nhân dân Trung Hoa(Số đăng ký bản quyền của Cục Bản quyền Quốc gia Trung Quốc 2018SR024463)，Bản quyền được bảo vệ bởi pháp luật và các điều ước quốc tế. Phần dưới cùng của trang web và các liên kết chính thức tương ứng không được xóa nếu không có sự cho phép bằng văn bản của chúng tôi. Để mua giấy phép thương mại, vui lòng liên hệ Zhongbang Technology để được hướng dẫn mới nhất.
8. Phần mềm này phù hợp với môi trường hoạt động. Nó đã được nêu rõ trong các tài liệu liên quan đến phần mềm. Chúng tôi không chịu bất kỳ trách nhiệm nào đối với các trục trặc do cài đặt phần mềm không phù hợp với môi trường hoạt động.。
<br/>
3. Hạn chế quyền
1. Hạn chế sử dụng một lần: Tên miền giống nhau chỉ được phép ràng buộc một lần. Giấy phép bạn mua chỉ dành cho mục đích sử dụng của riêng bạn và có thể không được cấp phép cho bất kỳ bên thứ ba nào.
2. Hạn chế chia sẻ phần mềm: Bạn không được phép cho phép nhiều người sử dụng một số hoặc tất cả chức năng của phần mềm bằng cách chia sẻ toàn bộ hoặc một phần phần mềm.
3. Hạn chế phân rã phần mềm: Bạn không được phân tách phần mềm để nhúng các chức năng khác nhau hoặc các phần khác nhau của phần mềm vào các hệ thống phần mềm khác.
4. Hạn chế về tính toàn vẹn của phần mềm: Bạn không được phép xóa bất kỳ tuyên bố hoặc lời nhắc về bản quyền nào trong phần mềm, bạn cũng không được phép xóa, sửa đổi hoặc xóa bất kỳ nhãn hiệu hoặc biểu tượng nào xuất hiện trong phần mềm, trừ khi bạn có được sự đồng ý bằng văn bản của chúng tôi. Bạn nên thông báo cho chúng tôi bằng văn bản về các logo cần được sửa đổi và các chi tiết khác để chúng tôi có thể đánh giá nhu cầu của bạn.
5. Hạn chế về kỹ thuật đảo ngược, dịch ngược và tháo gỡ: Bạn không được phép thiết kế đảo ngược, dịch ngược hoặc tháo rời phần mềm trừ khi những hành động này được pháp luật cho phép rõ ràng.
6. Hạn chế chuyển nhượng: Bạn không được tiết lộ, chuyển giao, thuê, cho mượn, cấp phép lại hoặc phân phối tất cả hoặc bất kỳ phần nào của phần mềm hoặc một bản sao lưu duy nhất của phần mềm cho bên thứ ba mà không có sự đồng ý bằng văn bản của Zhongbang Technology.
7. Hạn chế về bảo mật: Bạn không được tiết lộ hiệu suất của phần mềm này hoặc bất kỳ đánh giá, kết quả kiểm tra hoặc bí mật kỹ thuật nào khác cho bất kỳ bên thứ ba nào mà không có sự đồng ý bằng văn bản của Zhongbang Technology。
<br/>
4. Quyền được bảo lưu
1. Zhongbang Technology bảo lưu theo quy định của pháp luật tất cả các quyền khác không được cấp rõ ràng cho bạn trong thỏa thuận này và về mặt pháp lý thuộc về Zhongbang Technology.
2. Phần mềm này được bảo vệ bởi luật bản quyền, hiệp ước bản quyền quốc tế và các luật sở hữu trí tuệ hoặc hiệp ước quốc tế khác. Theo Thỏa thuận này, theo đây, bạn chỉ được cấp quyền cấp phép chung, không độc quyền, không độc quyền để sử dụng Phần mềm, không được bán hoặc chuyển nhượng.
3. Quyền nhãn hiệu: Thỏa thuận này không cấp cho bạn bất kỳ quyền nào liên quan đến bất kỳ nhãn hiệu hoặc nhãn hiệu dịch vụ nào của Zhongbang Technology hoặc nhà cung cấp của nó.
4. Tất cả các quyền sở hữu trí tuệ liên quan đến phần mềm này, bao gồm nhưng không giới hạn ở quyền sáng chế, bản quyền, quyền thương hiệu, bí mật thương mại và bí mật kỹ thuật, đều là tài sản của chủ sở hữu nội dung tương ứng. Zhongbang Technology có quyền thu được lợi ích từ quyền sở hữu trí tuệ mà mình sở hữu.
5. Không được phép thuê, bán, thế chấp hoặc cấp phép lại phần mềm này hoặc giấy phép thương mại đi kèm với nó nếu không có sự cho phép bằng văn bản của chúng tôi.
6. Nghiêm cấm phát triển bất kỳ phiên bản phái sinh, sửa đổi hoặc bên thứ ba nào để phân phối lại dựa trên toàn bộ hoặc bất kỳ phần nào của CRMEB mà không có sự cho phép bằng văn bản của chúng tôi.
7. Sau khi bạn xác nhận thỏa thuận này và cài đặt CRMEB, bạn được coi là đã hiểu đầy đủ và chấp nhận các điều khoản của thỏa thuận này. Trong khi tận hưởng các quyền hạn được cấp bởi các điều khoản trên, bạn phải tuân theo các hạn chế và hạn chế có liên quan. Hành vi vượt quá phạm vi của thỏa thuận sẽ trực tiếp vi phạm thỏa thuận cấp phép này và cấu thành hành vi vi phạm. Chúng tôi có quyền chấm dứt ngay việc ủy ​​quyền, ra lệnh dừng thiệt hại và có quyền theo đuổi các trách nhiệm liên quan.。
<br/>
5. Quyền sở hữu trí tuệ
1. Chúng tôi sở hữu bản quyền, bí mật thương mại và các quyền sở hữu trí tuệ liên quan khác của phần mềm được cấp phép, bao gồm nhiều tài liệu khác nhau liên quan đến phần mềm được cấp phép. Các logo có liên quan của phần mềm được cấp phép thuộc quyền sở hữu trí tuệ của chúng tôi và các công ty liên kết của chúng tôi và được bảo vệ bởi các luật và quy định có liên quan.
2. Nếu không có sự đồng ý rõ ràng của chúng tôi, bạn không được sao chép, bắt chước, sử dụng hoặc xuất bản các biểu tượng trên, bạn cũng không được sửa đổi hoặc xóa bất kỳ logo, biểu tượng hoặc thông tin nhận dạng nào phản ánh chúng tôi và các chi nhánh của chúng tôi trong các sản phẩm ứng dụng.
3. Nếu không có sự đồng ý trước bằng văn bản của chúng tôi và các chi nhánh của chúng tôi, bạn không được thực hiện, sử dụng, chuyển giao hoặc cho phép bất kỳ bên thứ ba nào thực hiện, sử dụng hoặc chuyển giao các quyền sở hữu trí tuệ nêu trên cho bất kỳ mục đích kiếm lợi nhuận hoặc phi lợi nhuận nào.
4. Trừ khi được cho phép hoặc cấp phép rõ ràng ở đây, thỏa thuận này không liên quan đến bất kỳ hoạt động chuyển giao công nghệ nào. Tất cả các quyền, quyền tài sản và lợi ích có trong và liên quan đến phần mềm chỉ thuộc về chúng tôi. Trừ khi được cho phép rõ ràng ở đây, hợp đồng này không chuyển giao bất kỳ công nghệ nào cho bạn。
<br/>
6. Phiên bản nâng cấp
1. Chúng tôi sẽ thực hiện một loạt nâng cấp miễn phí khi cần thiết. Bạn chỉ có thể tận hưởng các bản nâng cấp phần mềm miễn phí sau khi có được giấy phép sử dụng thương mại. Chúng tôi có quyền quyết định thời điểm và cách thức gửi gói nâng cấp cho bạn.
2. Giấy phép cho phiên bản nâng cấp: Nếu phần mềm được nâng cấp với sự đồng ý của Zhongbang Technology, trừ khi phiên bản nâng cấp có thỏa thuận cấp phép phần mềm thay thế, phiên bản nâng cấp vẫn phải tuân thủ các điều khoản của thỏa thuận này.
3. Bất kể phần mềm có được nâng cấp hay không, bạn đều phải tuân thủ thỏa thuận này。
<br/>
7. Không đảm bảo và giới hạn trách nhiệm pháp lý
1. Ngoại trừ các vấn đề được Zhongbang Technology bảo đảm rõ ràng, nó không đưa ra bất kỳ bảo đảm ngụ ý hoặc rõ ràng nào khác cho bất kỳ mục đích ngầm, cụ thể hoặc khả năng bán được nào khác và những rủi ro phát sinh từ đó sẽ do bạn chịu.
2. Về việc phần mềm này không thể áp dụng trong quá trình sử dụng, bạn nên phản hồi ngay bằng văn bản cho chúng tôi. Nếu công nghệ hiện tại của chúng tôi có thể giải quyết được vấn đề, chính sách bảo hành tiêu chuẩn sản phẩm phần mềm của Zhongbang Technology sẽ được tuân thủ.。
    1)Zhongbang Technology không chịu bất kỳ trách nhiệm pháp lý rõ ràng hay ngụ ý nào đối với những tổn thất phát sinh từ việc sử dụng thời gian dùng thử và phần mềm dùng thử miễn phí.。
    2)Mọi trách nhiệm pháp lý của Zhongbang Technology được giới hạn ở mức giá bạn đã trả để mua phần mềm.
3. Chúng tôi không chịu bất kỳ trách nhiệm nào đối với các vấn đề sử dụng phần mềm do tai nạn, lạm dụng, sử dụng sai hoặc sửa đổi trái phép và chúng tôi không đưa ra bất kỳ đảm bảo nào. Chúng tôi không chịu bất kỳ trách nhiệm nào và không đưa ra bất kỳ đảm bảo nào nếu phần mềm không thể sử dụng được hoặc xảy ra tổn thất do sản phẩm phần mềm bị tấn công, các yếu tố bất khả kháng như thiên tai hoặc các lý do khác ngoài Công nghệ Zhongbang.
4. Zhongbang Technology không chịu bất kỳ trách nhiệm nào đối với bất kỳ tổn thất ngẫu nhiên, gián tiếp hoặc mang tính trừng phạt nào khác do việc sử dụng phần mềm gây ra, bao gồm nhưng không giới hạn ở việc mất lợi nhuận kinh doanh, mất thông tin hoặc dữ liệu, ngay cả khi Zhongbang Technology đã được thông báo về khả năng xảy ra thiệt hại đó.
5. Trừ khi luật pháp và quy định có quy định khác, chúng tôi sẽ cố gắng hết sức để đảm bảo rằng phần mềm được cấp phép cũng như công nghệ và thông tin liên quan là an toàn, hiệu quả, chính xác và đáng tin cậy. Tuy nhiên, do những hạn chế về công nghệ hiện tại của chúng tôi, bạn hoàn toàn hiểu rằng chúng tôi không thể đảm bảo điều này. Bạn hiểu rằng chúng tôi không thể chịu trách nhiệm về những tổn thất trực tiếp hoặc gián tiếp do chính bạn gây ra, bất khả kháng và lý do của bên thứ ba.
6. Bạn phải tự chịu trách nhiệm về mọi thương tích cá nhân hoặc thiệt hại ngẫu nhiên hoặc gián tiếp gây ra bởi hoặc liên quan đến bất kỳ trường hợp nào sau đây, bao gồm nhưng không giới hạn ở việc mất lợi nhuận, mất dữ liệu, thiệt hại do gián đoạn kinh doanh hoặc các thiệt hại hoặc mất mát thương mại khác: sử dụng hoặc không sử dụng phần mềm được cấp phép; việc sử dụng phần mềm được cấp phép không được chấp thuận hoặc bên thứ ba thay đổi dữ liệu của bạn; chi phí và tổn thất phát sinh từ việc sử dụng phần mềm được cấp phép; sự hiểu lầm của bạn về phần mềm được cấp phép; những tổn thất khác liên quan đến phần mềm được cấp phép mà không phải do chúng tôi gây ra.
7. Bất kỳ phần mềm nào khác có nguồn gốc từ phần mềm được cấp phép không do chúng tôi phát triển và phát hành chính thức hoặc sự ủy quyền của chúng tôi là bất hợp pháp. Việc tải xuống, cài đặt và sử dụng phần mềm đó hoặc không ràng buộc một tên miền duy nhất có thể dẫn đến những rủi ro khó lường. Các trách nhiệm pháp lý và tranh chấp phát sinh không liên quan gì đến chúng tôi. Chúng tôi có quyền đình chỉ hoặc chấm dứt giấy phép và/hoặc tất cả các dịch vụ khác.
8. Khi bạn và những người dùng khác của phần mềm được cấp phép giao tiếp thông qua phần mềm được cấp phép, mọi tổn hại về tâm lý, sinh lý và kinh tế có thể xảy ra do bạn bị lừa dối hoặc lừa dối sẽ do bên vi phạm chịu theo quy định của pháp luật.。
<br/>
8. Điều khoản bảo mật
Cả hai bên phải giữ bí mật về kế hoạch kinh doanh, thông tin khách hàng, công nghệ, sản phẩm, mã số, tài liệu và các thông tin bí mật khác của bên kia mà có thể được biết là bí mật thương mại của bên đó. Thông tin bí mật bao gồm tất cả thông tin, hữu hình hoặc vô hình, được đánh dấu là bí mật. Thông tin bí mật vẫn là tài sản của bên tiết lộ và không được tiết lộ hoặc sử dụng trừ khi có sự cho phép rõ ràng của bên tiết lộ.。
<br/>
9. Chấm dứt thỏa thuận và trách nhiệm pháp lý khi vi phạm hợp đồng
1. Nếu bạn không tuân thủ một số hoặc tất cả các điều khoản của thỏa thuận này, Zhongbang Technology có thể đơn phương chấm dứt thỏa thuận này bất cứ lúc nào. Sau khi chấm dứt thỏa thuận, chúng tôi sẽ hủy ủy quyền giấy phép thương mại của bạn và bạn phải ngừng sử dụng phần mềm ngay lập tức và gỡ cài đặt phần mềm đã cài đặt. Nếu bạn gây thiệt hại cho Zhongbang Technology do vi phạm các quy định của thỏa thuận này, bạn sẽ phải chịu trách nhiệm bồi thường thiệt hại.
2. Bạn nên hiểu rằng việc sử dụng phần mềm được cấp phép trong phạm vi được ủy quyền, tôn trọng quyền sở hữu trí tuệ của phần mềm và nội dung có trong phần mềm, sử dụng phần mềm theo đúng thông số kỹ thuật và thực hiện các nghĩa vụ theo thỏa thuận này là những điều kiện tiên quyết để bạn nhận được sự cho phép của chúng tôi để sử dụng phần mềm. Nếu bạn vi phạm thỏa thuận này, chúng tôi có quyền chấm dứt giấy phép.
3. Việc bạn sử dụng phần mềm phụ thuộc vào các dịch vụ hỗ trợ do chúng tôi và các chi nhánh của chúng tôi cung cấp. Nếu bạn vi phạm các điều khoản, thỏa thuận, quy tắc, thông báo và các quy định liên quan khác với chúng tôi hoặc các chi nhánh của chúng tôi, chúng tôi có quyền chấm dứt giấy phép. Nếu bạn vi phạm các quy định của thỏa thuận này và gây thiệt hại cho Zhongbang Technology, bạn sẽ phải chịu trách nhiệm bồi thường những tổn thất gây ra cho chúng tôi.
4. Bạn hiểu rằng vì mục đích duy trì trật tự của hệ thống phần mềm và nền tảng phần mềm, nếu bạn đưa ra bất kỳ hình thức cam kết nào với chúng tôi và/hoặc các công ty liên kết của chúng tôi và công ty liên quan đã xác nhận rằng bạn đã vi phạm cam kết đó và thông báo cho chúng tôi xử lý theo thỏa thuận liên quan của bạn, chúng tôi có thể thực hiện các biện pháp hạn chế đối với giấy phép sử dụng của bạn cũng như các quyền và lợi ích khác mà chúng tôi có thể kiểm soát theo cách được chỉ định trong cam kết hoặc thỏa thuận của bạn, bao gồm đình chỉ hoặc chấm dứt giấy phép sử dụng và truy đuổi trách nhiệm pháp lý của bạn.
5. Nếu bạn nhận được phần mềm được cấp phép từ bên thứ ba được chúng tôi ủy quyền, bạn cần tuân thủ thỏa thuận này và thỏa thuận của bên thứ ba về các phương pháp cũng như hạn chế đối với việc bạn sử dụng phần mềm được cấp phép. Nếu bạn vi phạm thỏa thuận này và thỏa thuận với bên thứ ba, chúng tôi có quyền chấm dứt giấy phép của bạn và buộc bạn phải chịu trách nhiệm trước pháp luật có liên quan.
6. Bạn nên giữ bí mật mã, tài liệu và thông tin kỹ thuật khác thu được từ phần mềm này. Bạn không được xóa hoặc sửa đổi mã nguồn, tài liệu và khung, giải mã các phần được mã hóa hoặc bán lại phần mềm một cách bất hợp pháp. Chúng tôi không chịu bất kỳ trách nhiệm nào về hậu quả của việc sử dụng phần mềm bất hợp pháp và có quyền truy cứu trách nhiệm pháp lý của bạn. Bạn phải bồi thường cho chúng tôi những tổn thất trực tiếp và gián tiếp do hành vi vi phạm của bạn gây ra.
7. Nếu bạn vi phạm các điều khoản của thỏa thuận này, điều đó sẽ cấu thành hành vi vi phạm hợp đồng và bạn sẽ phải chịu thiệt hại từ mười lần đến năm mươi lần giá bán phần mềm. Nếu bạn gây tổn thất cho chúng tôi hoặc những người dùng khác, bạn phải chịu mọi trách nhiệm bồi thường (bao gồm tổn thất trực tiếp và tổn thất gián tiếp), bao gồm nhưng không giới hạn ở phí tư vấn, phí kiện tụng, phí thực hiện, phí bảo quản, phí bảo hiểm, phí luật sư, v.v.。
<br/>
10. Luật điều chỉnh và khả năng tách biệt
1、<strong>Hiệu lực, giải thích, sửa đổi, thực thi và giải quyết tranh chấp của Thỏa thuận này sẽ được điều chỉnh bởi luật pháp của Cộng hòa Nhân dân Trung Hoa. Trường hợp chưa có quy định pháp luật liên quan thì tham khảo thông lệ kinh doanh quốc tế chung và/hoặc thông lệ ngành. Thỏa thuận này được ký giữa bạn và chúng tôi tại Quận Liên Hồ, Thành phố Tây An, Tỉnh Thiểm Tây, nơi đặt máy chủ của chúng tôi. Đối với các tranh chấp phát sinh từ hoặc liên quan đến Thỏa thuận này, bạn có thể thương lượng một cách thân thiện với chúng tôi. Nếu đàm phán không thành công, tranh chấp sẽ được đưa ra Ủy ban Trọng tài Tây An để phân xử. Phán quyết trọng tài là cuối cùng và ràng buộc đối với cả hai bên.。</strong>
2、Nếu bất kỳ điều khoản nào của Thỏa thuận này bị coi là không hợp lệ thì điều khoản đó sẽ không ảnh hưởng đến hiệu lực của các điều khoản khác hoặc bất kỳ phần nào trong đó. Bạn và chúng tôi vẫn sẽ thực hiện chúng một cách thiện chí.。
<br/>
11. Hướng dẫn khác
1.<strong>CRMEBSản phẩm không thu thập bất kỳ thông tin riêng tư cá nhân nào của người dùng cuối。</strong>
2.Để đảm bảo tính ổn định và hợp pháp về bản quyền khi bạn sử dụng các sản phẩm và/hoặc dịch vụ của CRMEB, chúng tôi cần thu thập thông tin thiết bị của bạn (phiên bản hệ điều hành và phần mềm, tên miền trang cài đặt, địa chỉ IP, thông tin trình duyệt）。
<br/>
12. Các điều khoản khác
1. Nếu không có thỏa thuận nào trong thỏa thuận này thì hai bên sẽ tự thương lượng.
2. Tất cả các tiêu đề trong thỏa thuận này chỉ nhằm mục đích bắt mắt và dễ đọc. Chúng không có ý nghĩa thực tế và không thể được sử dụng làm cơ sở để giải thích ý nghĩa của thỏa thuận này.


                                                                   Công ty TNHH Công nghệ Mạng Tây An Zhongbang
                                                                Thời gian ban hành thỏa thuận: 01/08/2017
                                                            Trang web chính thức của CRMEB：https://www.crmeb.com

</pre>
        </div>
        <div class="bottom" @click="agree">tôi hiểu rồi</div>
    </div>
</div>
<?php require './templates/footer.php';?>

</body>
<script>
    new Vue({
        el: '#step1',
        data() {
            return { radio: 0,isShow: 0,isShock:false }
        },
        methods:{
            jump(){
                if(this.radio==1){
                    window.location.href = "./index.php?step=2";
                } else {
                    // this.$message({
                    //     message: 'Vui lòng đọc và đồng ý với "Thỏa thuận sử dụng phần mềm" trước khi chuyển sang bước tiếp theo.',
                    //     type: 'error'
                    // });
                    this.isShock = true
                    setTimeout(e=>{this.isShock = false},500)
                }
            },
            agree(){
                this.isShow = 0
            }
        }
    })
</script>
<script>
    console.log(`
          CCCCCCCCCCCCC  RRRRRRRRRRRRRRRRR     MMMMMMMM               MMMMMMMM  EEEEEEEEEEEEEEEEEEEEEE  BBBBBBBBBBBBBBBBB
       CCC::::::::::::C  R::::::::::::::::R    M:::::::M             M:::::::M  E::::::::::::::::::::E  B::::::::::::::::B
     CC:::::::::::::::C  R::::::RRRRRR:::::R   M::::::::M           M::::::::M  E::::::::::::::::::::E  B::::::BBBBBB:::::B
    C:::::CCCCCCCC::::C  RR:::::R     R:::::R  M:::::::::M         M:::::::::M  EE::::::EEEEEEEEE::::E  BB:::::B     B:::::B
   C:::::C       CCCCCC    R::::R     R:::::R  M::::::::::M       M::::::::::M    E:::::E       EEEEEE    B::::B     B:::::B
  C:::::C                  R::::R     R:::::R  M:::::::::::M     M:::::::::::M    E:::::E                 B::::B     B:::::B
  C:::::C                  R::::RRRRRR:::::R   M:::::::M::::M   M::::M:::::::M    E::::::EEEEEEEEEE       B::::BBBBBB:::::B
  C:::::C                  R:::::::::::::RR    M::::::M M::::M M::::M M::::::M    E:::::::::::::::E       B:::::::::::::BB
  C:::::C                  R::::RRRRRR:::::R   M::::::M  M::::M::::M  M::::::M    E:::::::::::::::E       B::::BBBBBB:::::B
  C:::::C                  R::::R     R:::::R  M::::::M   M:::::::M   M::::::M    E::::::EEEEEEEEEE       B::::B     B:::::B
  C:::::C                  R::::R     R:::::R  M::::::M    M:::::M    M::::::M    E:::::E                 B::::B     B:::::B
   C:::::C       CCCCCC  R::::R       R:::::R  M::::::M     MMMMM     M::::::M    E:::::E       EEEEEE    B::::B     B:::::B
    C:::::CCCCCCCC::::C  RR:::::R     R:::::R  M::::::M               M::::::M  EE::::::EEEEEEEE:::::E  BB:::::BBBBBB::::::B
     CC:::::::::::::::C  R::::::R     R:::::R  M::::::M               M::::::M  E::::::::::::::::::::E  B:::::::::::::::::B
       CCC::::::::::::C  R::::::R     R:::::R  M::::::M               M::::::M  E::::::::::::::::::::E  B::::::::::::::::B
          CCCCCCCCCCCCC  RRRRRRRR     RRRRRRR  MMMMMMMM               MMMMMMMM  EEEEEEEEEEEEEEEEEEEEEE  BBBBBBBBBBBBBBBBB

  Công nghệ Trung Bang https://www.crmeb.com/
        `)
</script>
</html>
