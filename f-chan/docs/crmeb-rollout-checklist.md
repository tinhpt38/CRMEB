# CRMEB Rollout Checklist (f-chan)

## Preconditions
1. Set `app-config.json -> template.apiUrl` to your CRMEB API base URL.
   - Example: `https://<host>/api/` (bất kỳ format nào có `/api/` cuối cùng thường hoạt động tốt nhất).
2. Ensure `template.features` is enabled as needed:
   - `catalog`: banners/categories/products
   - `orders`: order list/detail
   - `checkout`: checkout flow

## Feature Flags & Expected Behavior
When a feature flag is disabled, the app should fall back to existing mock endpoints under `src/mock/`.

## Manual E2E Flow
1. Home / Catalog
   - Open `/` and verify:
     - Banners carousel loads (no console error).
     - Category list renders.
     - Product cards render and navigate to `/category/:id` and `/product/:id`.
2. Orders (Login-required)
   - Navigate to `/orders/pending` (or any tab).
   - Verify orders list renders with correct statuses:
     - pending / shipping / completed
3. Order Detail
   - Click an order item and ensure detail page loads via server:
     - `GET /api/order/detail/:uni`
4. Checkout (Shipping only for MVP)
   - Add products to cart.
   - On Cart, keep delivery mode set to `shipping`:
     - If `pickup` is selected, checkout should block with a toast.
   - Tap Pay:
     - Verify requests succeed in order:
       1) `POST /api/cart/add`
       2) `POST /api/order/confirm`
       3) `POST /api/order/computed/:key`
       4) `POST /api/order/create/:key`
       5) `POST /api/order/pay`
   - After payment step, the app refreshes orders and navigates to `/orders`.

## Troubleshooting Notes
- If you see `Missing CRMEB api base URL`, verify `template.apiUrl` is set.
- If catalog loads but products images are broken, verify whether CRMEB returns absolute URLs for:
  - category `pic`
  - product `image` / `recommend_image`

