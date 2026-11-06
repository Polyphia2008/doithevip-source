<?php
require_once realpath($_SERVER["DOCUMENT_ROOT"]) . '/libs/init.php';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $slug         = anti_xss($_POST['slug'] ?? '');
    $current_page = max(1, (int)($_POST['page'] ?? 1));
    $price        = anti_xss($_POST['price'] ?? '');
    $id           = (int)($_POST['id'] ?? 0);

    /* ======================
    GET GROUP
    ====================== */
    $get_group = $db->get_row("
        SELECT * FROM `groups_account`
        WHERE `slug` = '{$slug}'
        AND `status` = 1
    ");

    if (!$get_group) {
        die('');
    }

    $group_id = (int)$get_group['id'];

    /* ======================
    GET FILTER CONFIG
    ====================== */
    $sample = $db->get_row("
        SELECT `detail` FROM `accounts`
        WHERE `group_id` = '{$group_id}'
        AND `status` = 1
    ");

    $arr_query = [];

    if ($sample && !empty($sample['detail'])) {
        $tmp = json_decode($sample['detail'], true);
        if (is_array($tmp) && isset($tmp['data']) && is_array($tmp['data'])) {
            $arr_query = $tmp['data'];
        }
    }

    /* ======================
    BUILD JSON FILTER
    ====================== */
    $sql = [];

    if (!empty($arr_query)) {
        foreach ($arr_query as $index => $field) {
            if (($field['show'] ?? '') === 'on') {
                $field_name = $field['name'] ?? '';
                if ($field_name && isset($_POST[$field_name]) && $_POST[$field_name] !== '') {
                    $value = anti_xss($_POST[$field_name]);
                    $sql[] = "AND JSON_UNQUOTE(JSON_EXTRACT(`detail`, '$.data[$index].value')) = '{$value}'";
                }
            }
        }
    }

    $implode = implode(' ', $sql);

    /* ======================
    FILTER ID
    ====================== */
    $sql_id = $id > 0 ? "AND `id` = '{$id}'" : '';

    /* ======================
    FILTER PRICE
    ====================== */
    $sql_price = '';
    switch ($price) {
        case 'duoi-50k':
            $sql_price = "AND `price` < 50000";
            break;
        case 'tu-50k-200k':
            $sql_price = "AND `price` BETWEEN 50000 AND 200000";
            break;
        case 'tu-200k-500k':
            $sql_price = "AND `price` BETWEEN 200000 AND 500000";
            break;
        case 'tu-500k-1-trieu':
            $sql_price = "AND `price` BETWEEN 500000 AND 1000000";
            break;
        case 'tren-1-trieu':
            $sql_price = "AND `price` >= 1000000";
            break;
        case 'tren-5-trieu':
            $sql_price = "AND `price` >= 5000000";
            break;
        case 'tren-10-trieu':
            $sql_price = "AND `price` >= 10000000";
            break;
    }

    /* ======================
    PAGINATION
    ====================== */
    $limit = 30;

    $total_records = $db->num_rows("
        SELECT id FROM `accounts`
        WHERE `group_id` = '{$group_id}'
        AND `status` = 1
        {$sql_id}
        {$sql_price}
        {$implode}
    ");

    $total_page = max(1, ceil($total_records / $limit));
    $current_page = min($current_page, $total_page);
    $start = ($current_page - 1) * $limit;

    $range  = 6;
    $middle = ceil($range / 2);

    if ($total_page <= $range) {
        $min = 1;
        $max = $total_page;
    } else {
        $min = max(1, $current_page - $middle + 1);
        $max = min($total_page, $min + $range - 1);
    }

    /* ======================
    GET LIST
    ====================== */
    $sql_show = "
        SELECT * FROM `accounts`
        WHERE `group_id` = '{$group_id}'
        AND `status` = 1
        {$sql_id}
        {$sql_price}
        {$implode}
        ORDER BY `id` ASC
        LIMIT {$start}, {$limit}
    ";
    if ($total_records > 0) {
        foreach ($db->get_list($sql_show) as $info) {
            $check = $db->get_row("SELECT * FROM `groups_account` WHERE `id` = '{$info['group_id']}'"); // lấy dữ liệu từ product
            $detail_product = json_decode($check['detail'], true); // get detail product
            $detail = json_decode($info['detail'], true); // detail list_account
            if ($check['type'] === 'tuchon') {
                $image_acc = '';
                if (!empty($info['image'])) {
                    $tmp = json_decode($info['image'], true);

                    if (is_array($tmp) && isset($tmp[0])) {
                        // JSON nhiều ảnh → lấy ảnh đầu tiên
                        $image_acc = $tmp[0];
                    } else {
                        // dữ liệu cũ dạng string
                        $image_acc = $info['image'];
                    }
                }

            } else {
                $image_acc = getRowRealtime('groups_account', $check['id'], 'image');
            }
            $discountPrice = getActiveProductFlashSale($info['id'], 'account');
            $feePercent = (float) $db->site('fees'); // ví dụ 5 (%)
            $fees       = $info['price'] * ($feePercent / 100);
            $priceWithFee = $info['price'] + $fees;

?>
<div class="ws-cursor-pointer ws-col-span-6 md:ws-col-span-4 lg:ws-col-span-3 ws-bg-white ws-rounded-lg hover:ws-shadow-sm">
    <?php if ($check['type'] == 'tuchon'): ?>
    <a href="/service/account/view-detail/<?=$info['id']?>" class="ws-bg-white ws-cursor-pointer">
    <?php else: ?>
    <a href="javascript:void(0)" onclick="confirmPurchase('<?=$info['id']?>')" class="ws-bg-white ws-cursor-pointer">
    <?php endif;?>
		<div class="ws-relative ws-w-full ws-inline-block ws-mb-0">
			    <?php if ($check['type'] == 'tuchon') {?>
                    <img class="ws-h-28 md:ws-h-40 ws-w-full ws-object-fill ws-object-center ws-rounded-t-sm ws-rounded-t-lg" src="<?=BASE_URL($image_acc);?>">
                <?php } else {?>
                    <img class="ws-h-28 md:ws-h-40 ws-w-full ws-object-fill ws-object-center ws-rounded-t-sm ws-rounded-t-lg" src="<?=BASE_URL($image_acc)?>">
                <?php }?>
			<span class="ws-bg-red-600 ws-absolute ws-top-[5px] ws-right-[5px] ws-text-xs ws-px-2 ws-py-0.5 ws-rounded ws-text-white">
				<b class="ws-font-bold">ID</b> <?=$info['id']?> 
			</span>
		</div>
		<div class="ws-py-1 ws-px-2 ws-text-red-500 ws-text-base">
			<div class="ws-flex ws-justify-between ws-items-center">
				<div class="ws-col-span-7">
				    <?php if ($check['type'] == 'tuchon'): ?>
                        <div class="ws-col-span-7">
                            <?php if ($discountPrice['flashSaleStatus'] === "ongoing"): ?>
                                <!-- Có flash sale -->
                                <span class="ws-text-sm ws-block">
                                    <span class="ws-line-through ws-text-zinc-500">
                                        <?= format_number($priceWithFee - ($priceWithFee * $info['sale'] / 100)) ?>
                                    </span>
                                    <small class="ws-text-zinc-500">đ</small>
                                    <small class="ws-ml-1 ws-font-semibold">-<?= format_number($discountPrice['discountPrice']) ?></small>
                                </span>
                                <span class="ws-font-semibold ws-inline-flex ws-items-start">
                                    <span class="ws-text-lg">
                                        <?= format_number(($priceWithFee - ($priceWithFee * $info['sale'] / 100)) - $discountPrice['discountPrice']) ?>

                                    </span>
                                    <small class="ws-ml-[0.5px]">đ</small>
                                </span>
                            <?php else: ?>
                                <!-- Không có flash sale -->
                                <span class="ws-font-semibold ws-inline-flex ws-items-start">
                                    <span class="ws-text-lg">
                                        <?= format_number($priceWithFee - ($priceWithFee * $info['sale'] / 100)) ?>
                                    </span>
                                    <small class="ws-ml-[0.5px]">đ</small>
                                </span>
                            <?php endif; ?>
                        </div>
                    <?php else: ?>
                        <span class="ws-font-semibold ws-inline-flex ws-items-start">
                            <span class="ws-text-lg">
                                <?= format_number(
                                    $discountPrice['flashSaleStatus'] === "ongoing" 
                                        ? ($priceWithFee - ($priceWithFee * $info['sale'] / 100)) - $discountPrice['discountPrice']
                                        : ($priceWithFee - ($priceWithFee * $info['sale'] / 100))
                                ) ?>

                            </span>
                            <small class="ws-ml-[0.5px]">đ</small>
                        </span>
                    <?php endif; ?>
				</div>
				<div class="ws-col-span-5 ws-text-right ws-flex ws-items-center ws-mb-1.5">
				    <?php if ($check['type'] == 'tuchon'): ?>
					    <button class="ws-text-xs ws-bg-red-500 ws-text-white ws-rounded ws-px-2 ws-py-1"> Xem<span class="ws-hidden md:ws-inline ws-ml-1">ngay</span></button>
					<?php else: ?>
            		    <button class="ws-text-xs ws-bg-red-500 ws-text-white ws-rounded ws-px-2 ws-py-1"> Mua<span class="ws-hidden md:ws-inline ws-ml-1">ngay</span></button>
            		<?php endif;?>
				</div>
			</div>
		</div>
		<div class="ws-text-sm ws-text-zinc-800 ws-border-t ws-border-dashed ws-py-1 ws-px-2">
            <!--[-->
            <?php
            $maxItems = 3;
            $count = 0;
            
            // đảm bảo $detail luôn là mảng
            $data = is_array($detail) ? $detail : [];
            
            foreach ($data as $item) {
                if (($item['show'] ?? '') === 'on') {
            
                    if ($count >= $maxItems) {
                        break;
                    }
            ?>
                <div class="ws-my-1">
                    <span class="ws-truncate ws-w-full ws-block">
                        <span class="ws-mr-1 ws-text-zinc-700 ws-cap">
                            <i class="ws-relative ws-top-[1px] fa-solid fa-caret-right"></i>
                            <?= htmlspecialchars($item['label'] ?? '') ?>:
                        </span>
                        <span class="ws-font-medium">
                            <?= anti_xss($item[$item['name']] ?? $item['value'] ?? '') ?>
                        </span>
                    </span>
                </div>
            <?php
                    $count++;
                }
            }
            ?>
            <!--]-->
        </div>
		<?php if ($check['type'] == 'tuchon'): ?>
		<div class="ws-py-2 ws-truncate ws-text-center ws-text-sm ws-font-medium ws-text-zinc-800 ws-px-2 ws-border-t ws-border-dashed"><i class="ws-text-red-600 ws-relative ws-top-[1px] fa-solid fa-fire"></i> NHẤN VÀO ĐỂ XEM CHI TIẾT</div>
		<?php else: ?>
		<div class="ws-py-2 ws-truncate ws-text-center ws-text-sm ws-font-medium ws-text-zinc-800 ws-px-2 ws-border-t ws-border-dashed"><i class="ws-text-red-600 ws-relative ws-top-[1px] fa-solid fa-fire"></i> THỬ VẬN MAY NGAY NÀO </div>
		<?php endif;?>
	</a>
</div>


<?php } } else {?>

<?php }?>

<!-- qua trang -->
<div class="ws--mx-2 ws-flex">
    <div class="el-pagination is-background">
        <button type="button" class="btn-prev is-first" <?= $current_page <= 1 ? 'disabled aria-disabled="true"' : '' ?> aria-label="Go to previous page" onclick="page=<?= $current_page - 1 ?>;load_account()">
            <i class="el-icon">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1024 1024">
                    <path fill="currentColor" d="M609.408 149.376 277.76 489.6a32 32 0 0 0 0 44.672l331.648 340.352a29.12 29.12 0 0 0 41.728 0 30.592 30.592 0 0 0 0-42.752L339.264 511.936l311.872-319.872a30.592 30.592 0 0 0 0-42.688 29.12 29.12 0 0 0-41.728 0z"></path>
                </svg>
            </i>
        </button>
        <ul class="el-pager">
            <?php if ($min > 1) { ?>
                <li class="number" onclick="page=1;load_account()">1</li>
                <li class="more btn-quicknext el-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1024 1024">
                        <path fill="currentColor" d="M176 416a112 112 0 1 1 0 224 112 112 0 0 1 0-224m336 0a112 112 0 1 1 0 224 112 112 0 0 1 0-224m336 0a112 112 0 1 1 0 224 112 112 0 0 1 0-224"></path>
                    </svg>
                </li>
            <?php } ?>
            <?php for ($i = $min; $i <= $max; $i++) { ?>
                <li class="number <?= $current_page == $i ? 'is-active' : '' ?>" onclick="page=<?= $i ?>;load_account()"><?= $i ?></li>
            <?php } ?>
            <?php if ($max < $total_page) { ?>
                <li class="more btn-quicknext el-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1024 1024">
                        <path fill="currentColor" d="M176 416a112 112 0 1 1 0 224 112 112 0 0 1 0-224m336 0a112 112 0 1 1 0 224 112 112 0 0 1 0-224m336 0a112 112 0 1 1 0 224 112 112 0 0 1 0-224"></path>
                    </svg>
                </li>
                <li class="number" onclick="page=<?= $total_page ?>;load_account()"><?= $total_page ?></li>
            <?php } ?>
        </ul>
        <button type="button" class="btn-next is-last" <?= $current_page >= $total_page ? ' aria-disabled="true"' : '' ?> aria-label="Go to next page" onclick="page=<?= $current_page + 1 ?>;load_account()">
            <i class="el-icon">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1024 1024">
                    <path fill="currentColor" d="M340.864 149.312a30.592 30.592 0 0 0 0 42.752L652.736 512 340.864 831.872a30.592 30.592 0 0 0 0 42.752 29.12 29.12 0 0 0 41.728 0L714.24 534.336a32 32 0 0 0 0-44.672L382.592 149.376a29.12 29.12 0 0 0-41.728 0z"></path>
                </svg>
            </i>
        </button>
    </div>
</div>


<?php } else {
    die(json_encode(array('message' => "The requested resource does not support http method 'GET'.")));
}
?>