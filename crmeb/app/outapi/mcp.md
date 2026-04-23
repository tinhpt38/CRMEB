# CRMEB MCP Server

dựa trên Model Context Protocol (MCP) Máy chủ công cụ API CRMEB đã được tích hợp vào mô-đun outapi CRMEB, cho phép trợ lý AI gọi giao diện mở bên ngoài CRMEB thông qua các giao thức tiêu chuẩn.

## Đặc trưng

- 🔗 Được tích hợp vào mô-đun outapi CRMEB, không cần triển khai bổ sung
- 📦 Quản lý sản phẩm (danh sách, chi tiết, tạo）
- 📂 Quản lý danh mục (danh sách, chi tiết, tạo）
- 🛒 Quản lý đơn hàng (danh sách, chi tiết, vận chuyển）
- 💰 Quản lý sau bán hàng (danh sách, chi tiết, đồng ý/từ chối hoàn tiền）
- 🎫 Quản lý phiếu giảm giá
- 👥 Quản lý người dùng (danh sách, chi tiết, số dư/điểm thưởng)

## Yêu cầu về môi trường

- Hệ thống CRMEB
- PHP >= 7.4

## bắt đầu nhanh

### 1. Tạo tài khoản giao diện mở

1. Đăng nhập vào phần phụ trợ quản lý CRMEB
2. Nhập **Cài đặt** -> **Cài đặt hệ thống** -> **Giao diện mở**
3. Tạo ứng dụng lấy tài khoản và mật khẩu

### 2. Cấu hình cho Claude Desktop

Chỉnh sửa tập tin cấu hình：

**macOS**: `~/Library/Application Support/Claude/claude_desktop_config.json`
**Windows**: `%APPDATA%\Claude\claude_desktop_config.json`

```json
{
  "mcpServers": {
    "crmeb": {
      "url": "http://your-domain/outapi/mcp",
      "headers": {
        "account": "your_account",
        "password": "your_password",
        "Content-Type": "application/json"
      },
      "disabled": false
    }
  }
}
```

**Ví dụ về cấu hình (môi trường demo）：**
```json
{
  "mcpServers": {
    "crmebdemo": {
      "url": "https://v5.crmeb.net/outapi/mcp",
      "headers": {
        "account": "ceshi",
        "password": "ceshiceshi",
        "Content-Type": "application/json"
      },
      "disabled": false
    }
  }
}
```

### 3. Định cấu hình cho con trỏ

Thêm vào cài đặt Con trỏ：

```json
{
  "mcp.servers": {
    "crmeb": {
      "url": "http://your-domain/outapi/mcp",
      "headers": {
        "account": "your_account",
        "password": "your_password",
        "Content-Type": "application/json"
      },
      "disabled": false
    }
  }
}
```

**Mô tả thông số：**

| tham số | minh họa | Làm thế nào để có được nó |
|------|------|---------|
| `url` | CRMEB MCP địa chỉ giao diện | giá trị cố định：`http://tên miền/outapi/mcp` |
| `account` | Mở tài khoản giao diện | CRMEB Hậu trường -> cài đặt -> giao diện mở |
| `password` | Mở mật khẩu giao diện | CRMEB Hậu trường -> cài đặt -> giao diện mở |
| `disabled` | Có nên tắt dịch vụ hay không | Tùy chọn, mặc định là false |

> Lưu ý: Chỉ cần cấu hình trực tiếp tài khoản và mật khẩu, hệ thống sẽ tự động hoàn tất xác thực.

## Ứng dụng khách MCP được hỗ trợ

Sau đây là các ứng dụng chính hiện hỗ trợ giao thức MCP. Bạn có thể định cấu hình và sử dụng dịch vụ CRMEB MCP trong các ứng dụng này.

### 1. Máy tính để bàn Claude

Ứng dụng máy tính để bàn chính thức của Anthropic, ứng dụng khách đầu tiên hỗ trợ MCP nguyên bản.

**Các bước cấu hình:**
1. Tải xuống và cài đặt [Claude Desktop](https://claude.ai/download)
2. Tìm tập tin cấu hình：
   - **macOS**: `~/Library/Application Support/Claude/claude_desktop_config.json`
   - **Windows**: `%APPDATA%\Claude\claude_desktop_config.json`
3. Thêm cấu hình CRMEB MCP：
```json
{
  "mcpServers": {
    "crmeb": {
      "url": "http://your-domain/outapi/mcp",
      "headers": {
        "account": "your_account",
        "password": "your_password",
        "Content-Type": "application/json"
      },
      "disabled": false
    }
  }
}
```
4. Khởi động lại máy tính để bàn Claude

**Cách sử dụng:**
Hỏi trực tiếp trong cuộc trò chuyện, ví dụ:：
```
Giúp tôi truy vấn danh sách phân loại sản phẩm trong CRMEB
```

### 2. Cursor

AI Trình chỉnh sửa mã điều khiển bằng trình điều khiển có hỗ trợ MCP tích hợp.

**Các bước cấu hình:**
1. Tải xuống và cài đặt [Cursor](https://cursor.sh/)
2. Mở cài đặt（Settings -> Features -> Model Context Protocol）
3. Thêm cấu hình máy chủ MCP：
```json
{
  "mcp.servers": {
    "crmeb": {
      "url": "http://your-domain/outapi/mcp",
      "headers": {
        "account": "your_account",
        "password": "your_password",
        "Content-Type": "application/json"
      },
      "disabled": false
    }
  }
}
```
4. Hoặc chỉnh sửa trực tiếp file cấu hình：
   - **macOS/Linux**: `~/.cursor/mcp.json`
   - **Windows**: `%APPDATA%\Cursor\mcp.json`

**Cách sử dụng: **
Sử dụng trực tiếp trong cửa sổ trò chuyện AI của Cursor：
```
Truy vấn danh sách đơn hàng trong CRMEB
```

### 3. Cline (VS Code Mở rộng)

VS Code Tiện ích mở rộng trợ lý lập trình AI tự động trong .

**Các bước cấu hình:**
1. Cài đặt trong Mã VS [Cline Mở rộng](https://marketplace.visualstudio.com/items?itemName=saoudrizwan.claude-dev)
2. Mở cài đặt Mã VS
3. Tìm kiếm "Cline MCP" hoặc tìm cấu hình MCP trong bảng Cline
4. Thêm máy chủ MCP：
```json
{
  "mcpServers": {
    "crmeb": {
      "url": "http://your-domain/outapi/mcp",
      "headers": {
        "account": "your_account",
        "password": "your_password",
        "Content-Type": "application/json"
      },
      "disabled": false
    }
  }
}
```

**Cách sử dụng: **
Nhập lệnh trong bảng Cline：
```
Nhận thông tin chi tiết về ID sản phẩm CRMEB 1
```

### 4. Windsurf

Codeium Ra mắt IDE gốc AI.

**Các bước cấu hình:**
1. Tải xuống và cài đặt [Windsurf](https://codeium.com/windsurf)
2. Mở cài đặt -> Developer Settings -> MCP Servers
3. Thêm cấu hình：
```json
{
  "mcpServers": {
    "crmeb": {
      "url": "http://your-domain/outapi/mcp",
      "headers": {
        "account": "your_account",
        "password": "your_password",
        "Content-Type": "application/json"
      }
    }
  }
}
```

### 5. Continue (VS Code/JetBrains Mở rộng)

Phần mở rộng trợ lý mã nguồn mở AI.

**Các bước cấu hình:**
1. Cài đặt tiện ích mở rộng Continue
   - [VS Code](https://marketplace.visualstudio.com/items?itemName=Continue.continue)
   - [JetBrains](https://plugins.jetbrains.com/plugin/22707-continue)
2. Mở tệp cấu hình Tiếp tục（`~/.continue/config.json`）
3. Thêm cấu hình MCP：
```json
{
  "models": [...],
  "mcpServers": {
    "crmeb": {
      "url": "http://your-domain/outapi/mcp",
      "headers": {
        "account": "your_account",
        "password": "your_password",
        "Content-Type": "application/json"
      }
    }
  }
}
```

### 6. Zed

Trình chỉnh sửa mã hiệu suất cao hỗ trợ giao thức MCP.

**Các bước cấu hình:**
1. Tải xuống và cài đặt [Zed](https://zed.dev/)
2. Mở tập tin cấu hình（`~/.zed/settings.json`）
3. Thêm cấu hình máy chủ MCP：
```json
{
  "mcp_servers": {
    "crmeb": {
      "url": "http://your-domain/outapi/mcp",
      "headers": {
        "account": "your_account",
        "password": "your_password",
        "Content-Type": "application/json"
      }
    }
  }
}
```

### 7. Các ứng dụng hỗ trợ MCP khác

Các ứng dụng sau đây cũng đang dần hỗ trợ giao thức MCP:

- **Codeium**: Công cụ hoàn thiện mã AI
- **Tabnine**: Trợ lý mã AI
- **Sourcegraph Cody**: mã trợ lý thông minh

> 💡 **Mẹo**: MCP là một giao thức mở và ngày càng được nhiều ứng dụng AI hỗ trợ. Nếu ứng dụng của bạn hỗ trợ MCP, bạn thường có thể tìm thấy các mục cấu hình liên quan đến MCP hoặc Giao thức bối cảnh mô hình trong cài đặt.

## Công cụ có sẵn

### Quản lý phân loại

| Tên công cụ | mô tả | Thông số bắt buộc | Thông số tùy chọn |
|---------|------|---------|---------|
| `crmeb_category_list` | Nhận danh sách danh mục | - | page, limit |
| `crmeb_category_detail` | Nhận chi tiết danh mục | id | - |
| `crmeb_category_create` | Tạo danh mục | name | pid, sort |

### Quản lý sản phẩm

| Tên công cụ | mô tả | Thông số bắt buộc | Thông số tùy chọn |
|---------|------|---------|---------|
| `crmeb_product_list` | Nhận danh sách sản phẩm | - | page, limit, cate_id, keyword, stock_min, stock_max |
| `crmeb_product_detail` | Nhận chi tiết sản phẩm | id | - |
| `crmeb_product_create` | Tạo sản phẩm | name, cate_id, price, stock | image, unit |

### Quản lý đơn hàng

| Tên công cụ | mô tả | Thông số bắt buộc | Thông số tùy chọn |
|---------|------|---------|---------|
| `crmeb_order_list` | Nhận danh sách đặt hàng | - | page, limit, status, keyword |
| `crmeb_order_detail` | Nhận chi tiết đơn hàng | order_id | - |
| `crmeb_order_delivery` | Đơn hàng đã được vận chuyển | order_id, delivery_type | delivery_name, delivery_id |
| `crmeb_order_express_list` | Nhận danh sách các công ty logistics | - | - |

### Quản lý sau bán hàng

| Tên công cụ | mô tả | Thông số bắt buộc | Thông số tùy chọn |
|---------|------|---------|---------|
| `crmeb_refund_list` | Nhận danh sách sau bán hàng | - | page, limit |
| `crmeb_refund_detail` | Nhận thông tin chi tiết sau bán hàng | order_id | - |
| `crmeb_refund_agree` | Đồng ý hoàn tiền | order_id | - |
| `crmeb_refund_refuse` | Từ chối hoàn tiền | order_id, refuse_reason | - |

### Quản lý phiếu giảm giá

| Tên công cụ | mô tả | Thông số bắt buộc | Thông số tùy chọn |
|---------|------|---------|---------|
| `crmeb_coupon_list` | Nhận danh sách phiếu giảm giá | - | page, limit |

### Quản lý người dùng

| Tên công cụ | mô tả | Thông số bắt buộc | Thông số tùy chọn |
|---------|------|---------|---------|
| `crmeb_user_list` | Lấy danh sách người dùng | - | page, limit, keyword |
| `crmeb_user_detail` | Nhận thông tin chi tiết người dùng | uid | - |
| `crmeb_user_give_balance` | Số dư quà tặng | uid, balance | title |
| `crmeb_user_give_point` | Tặng điểm | uid, point | title |

## Ví dụ sử dụng

Trong Claude hoặc Cursor bạn có thể sử dụng：

```
Giúp tôi truy vấn danh sách sản phẩm trong CRMEB
```

```
Tạo sản phẩm mới: Tên "Sản phẩm thử nghiệm", ID loại 1, Giá 99,00, Còn hàng 100
```

```
Kiểm tra chi tiết mã đơn hàng 202403130001
```

```
Tặng 100 điểm cho người dùng có ID người dùng 1
```

## Kiểm tra giao diện

```bash
# Kiểm tra khởi tạo MCP
curl -X POST "http://localhost:8011/outapi/mcp" \
  -H "Content-Type: application/json" \
  -H "account: your_account" \
  -H "password: your_password" \
  -d '{"jsonrpc":"2.0","id":1,"method":"initialize","params":{}}'

# Lấy danh sách các công cụ
curl -X POST "http://localhost:8011/outapi/mcp" \
  -H "Content-Type: application/json" \
  -H "account: your_account" \
  -H "password: your_password" \
  -d '{"jsonrpc":"2.0","id":2,"method":"tools/list","params":{}}'

# Công cụ gọi - lấy danh sách sản phẩm
curl -X POST "http://localhost:8011/outapi/mcp" \
  -H "Content-Type: application/json" \
  -H "account: your_account" \
  -H "password: your_password" \
  -d '{"jsonrpc":"2.0","id":3,"method":"tools/call","params":{"name":"crmeb_product_list","arguments":{"page":1,"limit":10}}}'

# Công cụ gọi - Tạo danh mục
curl -X POST "http://localhost:8011/outapi/mcp" \
  -H "Content-Type: application/json" \
  -H "account: your_account" \
  -H "password: your_password" \
  -d '{"jsonrpc":"2.0","id":4,"method":"tools/call","params":{"name":"crmeb_category_create","arguments":{"name":"Danh mục mới","sort":100}}}'

# Công cụ gọi - Truy vấn sản phẩm có tồn kho lớn hơn 500
curl -X POST "http://localhost:8011/outapi/mcp" \
  -H "Content-Type: application/json" \
  -H "account: your_account" \
  -H "password: your_password" \
  -d '{"jsonrpc":"2.0","id":5,"method":"tools/call","params":{"name":"crmeb_product_list","arguments":{"stock_min":500}}}'

# Kiểm tra bằng môi trường demo
curl -X POST "https://v5.crmeb.net/outapi/mcp" \
  -H "Content-Type: application/json" \
  -H "account: ceshi" \
  -H "password: ceshiceshi" \
  -d '{"jsonrpc":"2.0","id":1,"method":"tools/call","params":{"name":"crmeb_category_list","arguments":{}}}'
```

## Cấu trúc dự án

```
crmeb/app/outapi/
├── controller/
│   ├── Mcp.php              # MCP bộ điều khiển
│   ├── Login.php            # Bộ điều khiển xác thực
│   ├── StoreProduct.php     # Bộ điều khiển sản phẩm
│   ├── StoreCategory.php    # Bộ điều khiển phân loại
│   ├── StoreOrder.php       # người điều khiển đơn hàng
│   ├── RefundOrder.php      # Bộ điều khiển hậu mãi
│   ├── StoreCoupon.php      # Bộ điều khiển phiếu giảm giá
│   ├── User.php             # bộ điều khiển người dùng
│   └── ...
├── route/
│   └── route.php            # Cấu hình định tuyến
├── middleware/
│   └── AuthTokenMiddleware.php
├── mcp.md                   # Tài liệu này
└── README.md                # outapi Mô tả mô-đun
```

## Mô tả giao thức

### Phiên bản giao thức MCP

- Phiên bản được hỗ trợ：`2024-11-05`

### JSON-RPC 2.0 định dạng

**Định dạng yêu cầu：**
```json
{
  "jsonrpc": "2.0",
  "id": 1,
  "method": "tools/call",
  "params": {
    "name": "Tên công cụ",
    "arguments": { "tham số": "giá trị" }
  }
}
```

**phản hồi thành công：**
```json
{
  "jsonrpc": "2.0",
  "id": 1,
  "result": {
    "content": [
      {
        "type": "text",
        "text": "{...Dữ liệu kết quả...}"
      }
    ]
  }
}
```

**phản hồi lỗi：**
```json
{
  "jsonrpc": "2.0",
  "id": 1,
  "error": {
    "code": -32603,
    "message": "thông báo lỗi"
  }
}
```

## Mô tả mã lỗi

| mã lỗi | minh họa |
|-------|------|
| -32700 | JSON Lỗi phân tích cú pháp |
| -32600 | Yêu cầu không hợp lệ (xác thực không thành công, v.v.)） |
| -32601 | phương pháp không tồn tại |
| -32603 | Lỗi nội bộ |

## Liên kết liên quan

- [Model Context Protocol tài liệu](https://modelcontextprotocol.io/)
- [CRMEB giao diện mở](./README.md)
