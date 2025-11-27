<?php
/**
 * Template Name: Hội đồng chuyên gia
 *
 * Trang tuỳ chỉnh hiển thị mạng lưới chuyên gia cho theme GiaiMatUKy.
 *
 * @package giaimatuky
 */

defined( 'ABSPATH' ) || exit;

get_header();

$experts           = array();
$category_map      = array();
$fallback_category = __( 'Khác', 'giaimatuky' );

function gmk_get_demo_experts() {
	return array(
		array(
			'id'          => 1,
			'name'        => 'Dr Eva Wong',
			'title'       => 'Tiến sĩ Tâm lý Giáo dục',
			'category'    => 'Tâm lý & Giáo dục',
			'location'    => 'Malaysia',
			'avatar'      => 'https://ui-avatars.com/api/?name=Eva+Wong&background=0f766e&color=fff&size=256',
			'credentials' => array(
				'Tiến sĩ Tâm lý Giáo dục (Đại học công nghệ Malaysia)',
				'Thạc sĩ Giáo dục Đặc biệt (Đại học Nam Queensland, Úc)',
				'Thạc sĩ Tính dục con người (Đại học Shu-te, Đài Loan)',
				'Chứng chỉ huấn luyện viên Giáo dục Đặc biệt (Vương quốc Anh)',
				'Nhà trị liệu phản hồi thần kinh được chứng nhận',
			),
		),
		array(
			'id'          => 2,
			'name'        => 'Ms Seri',
			'title'       => 'Nhà trị liệu vận động',
			'category'    => 'Trị liệu vận động',
			'location'    => 'Kuala Lumpur',
			'avatar'      => 'https://ui-avatars.com/api/?name=Seri&background=be185d&color=fff&size=256',
			'credentials' => array(
				'Cử nhân Trị liệu vận động – Đại học Teknologi Mara',
				'Thành viên Hiệp hội Trị liệu vận động Malaysia (MOTA)',
				'Chứng nhận OT (2012) – Bộ Y tế Malaysia',
				'Kinh nghiệm tại Bệnh viện KPJ Tawakkal',
			),
		),
		array(
			'id'          => 3,
			'name'        => 'Ms Xin Yi',
			'title'       => 'Thạc sĩ ABA',
			'category'    => 'Tâm lý & Giáo dục',
			'location'    => 'UK / Malaysia',
			'avatar'      => 'https://ui-avatars.com/api/?name=Xin+Yi&background=4338ca&color=fff&size=256',
			'credentials' => array(
				'Cử nhân Tâm lý – Đại học Northumbria',
				'Thạc sĩ ABA – Đại học South Wales',
				'10+ năm can thiệp hành vi cho TEA',
				'Được cấp chứng nhận BCBA',
			),
		),
		array(
			'id'          => 4,
			'name'        => 'Dr Yee Kok Wah',
			'title'       => 'Tiến sĩ, Bác sĩ Y khoa',
			'category'    => 'Y khoa',
			'location'    => 'Hong Kong / Malaysia',
			'avatar'      => 'https://ui-avatars.com/api/?name=Yee+Kok+Wah&background=334155&color=fff&size=256',
			'credentials' => array(
				'Tiến sĩ Quản lý y tế tích hợp – Southwest State University',
				'Thạc sĩ Khoa học Y học tái tạo – UCSI',
				'Bác sĩ Y khoa & Phẫu thuật – Melaka Manipal',
				'Chuyên gia tự kỉ nâng cao (IBCCES)',
			),
		),
		array(
			'id'          => 5,
			'name'        => 'Leong Cin Dee',
			'title'       => 'Chuyên gia Âm ngữ trị liệu',
			'category'    => 'Âm ngữ trị liệu',
			'location'    => 'Malaysia',
			'avatar'      => 'https://ui-avatars.com/api/?name=Leong+Cin+Dee&background=d97706&color=fff&size=256',
			'credentials' => array(
				'Cử nhân Khoa học Ngôn ngữ – ĐHQG Malaysia',
				'Âm ngữ trị liệu cấp phép Bộ Y tế Malaysia',
				'Chứng nhận More Than Words – Hanen',
				'Đào tạo PROMPT & Lidcombe',
			),
		),
		array(
			'id'          => 6,
			'name'        => 'Maznah Ibrahim',
			'title'       => 'Chuyên gia Âm ngữ trị liệu',
			'category'    => 'Âm ngữ trị liệu',
			'location'    => 'Malaysia',
			'avatar'      => 'https://ui-avatars.com/api/?name=Maznah+Ibrahim&background=e11d48&color=fff&size=256',
			'credentials' => array(
				'Hành nghề với Hội đồng Cố vấn Malaysia từ 2007',
				'Thạc sĩ Tư vấn – Đại học Putra Malaysia',
				'18 năm kinh nghiệm tâm lý trẻ em & thanh thiếu niên',
			),
		),
	);
}

$experts_query = new WP_Query(
    array(
        'post_type'      => 'gmk_expert',
        'posts_per_page' => -1,
        'orderby'        => array(
            'menu_order' => 'ASC',
            'title'      => 'ASC',
        ),
    )
);

if ( $experts_query->have_posts() ) {
    while ( $experts_query->have_posts() ) {
        $experts_query->the_post();

        $post_id     = get_the_ID();
        $job_title   = get_post_meta( $post_id, 'gmk_expert_job_title', true );
        $location    = get_post_meta( $post_id, 'gmk_expert_location', true );
        $credentials = get_post_meta( $post_id, 'gmk_expert_credentials', true );
        $avatar_id   = (int) get_post_meta( $post_id, 'gmk_expert_avatar_id', true );
        $terms       = wp_get_post_terms( $post_id, 'gmk_expert_domain', array( 'fields' => 'names' ) );

        if ( ! empty( $terms ) ) {
            foreach ( $terms as $term_name ) {
                $category_map[ $term_name ] = true;
            }
        } else {
            $category_map[ $fallback_category ] = true;
        }

        $primary_category = ! empty( $terms ) ? $terms[0] : $fallback_category;
        $credentials_list = array_values(
            array_filter(
                array_map(
                    'trim',
                    preg_split( '/\r\n|\r|\n/', (string) $credentials )
                )
            )
        );

        if ( empty( $credentials_list ) ) {
            $credentials_list[] = __( 'Đang cập nhật thông tin', 'giaimatuky' );
        }

        $avatar_url = $avatar_id ? wp_get_attachment_image_url( $avatar_id, 'medium' ) : get_the_post_thumbnail_url( $post_id, 'medium' );
        if ( ! $avatar_url ) {
            $avatar_url = 'https://ui-avatars.com/api/?name=' . rawurlencode( get_the_title() ) . '&background=0f172a&color=fff&size=256';
        }

        $experts[] = array(
            'id'          => $post_id,
            'name'        => get_the_title(),
            'title'       => $job_title ? $job_title : __( 'Chuyên gia', 'giaimatuky' ),
            'category'    => $primary_category,
            'location'    => $location ? $location : __( 'Đang cập nhật', 'giaimatuky' ),
            'avatar'      => $avatar_url,
            'credentials' => $credentials_list,
        );
    }
    wp_reset_postdata();
}

if ( empty( $experts ) ) {
	$experts = gmk_get_demo_experts();
	foreach ( $experts as $demo_expert ) {
		if ( ! empty( $demo_expert['category'] ) ) {
			$category_map[ $demo_expert['category'] ] = true;
		}
	}
}

$categories = array_merge( array( __( 'Tất cả', 'giaimatuky' ) ), array_keys( $category_map ) );
?>

<div class="gmk-shell gmk-experts-shell">
    <section class="gmk-expert-hero">
        <div class="gmk-expert-pill">DawnBridge · Scientific Council</div>
        <h1 class="gmk-expert-title">
            Hội đồng chuyên gia<br><span>Giáo dục · Y khoa · Trị liệu</span>
        </h1>
        <p class="gmk-expert-sub">
            Mạng lưới cố vấn đa ngành đã được thẩm định chuyên môn, đồng hành cùng phụ huynh và nhà trường trong các chương trình can thiệp chuẩn quốc tế.
        </p>
    </section>

    <section class="gmk-expert-filters">
        <div class="gmk-expert-search">
            <svg width="18" height="18" viewBox="0 0 24 24" aria-hidden="true" focusable="false">
                <path d="M21 21l-4.35-4.35M10.5 17a6.5 6.5 0 1 1 0-13 6.5 6.5 0 0 1 0 13z" stroke="currentColor" stroke-width="1.8" fill="none" stroke-linecap="round" />
            </svg>
            <input type="search" placeholder="Tìm theo tên, lĩnh vực, chứng chỉ..." id="gmk-expert-search">
        </div>
        <div class="gmk-expert-chips" role="tablist" aria-label="Bộ lọc chuyên môn">
            <?php foreach ( $categories as $index => $cat ) : ?>
                <button
                    type="button"
                    class="gmk-expert-chip <?php echo 0 === $index ? 'is-active' : ''; ?>"
                    data-category="<?php echo esc_attr( $cat ); ?>"
                    role="tab"
                    aria-selected="<?php echo 0 === $index ? 'true' : 'false'; ?>"
                >
                    <?php echo esc_html( $cat ); ?>
                </button>
            <?php endforeach; ?>
        </div>
    </section>

    <section class="gmk-expert-grid" id="gmk-expert-grid">
        <?php foreach ( $experts as $expert ) : ?>
            <article
                class="gmk-expert-card"
                data-expert='<?php echo esc_attr( wp_json_encode( $expert, JSON_UNESCAPED_UNICODE ) ); ?>'
            >
                <header class="gmk-expert-card__header">
                    <div class="gmk-expert-card__media">
                        <img src="<?php echo esc_url( $expert['avatar'] ); ?>" alt="<?php echo esc_attr( $expert['name'] ); ?>">
                    </div>
                    <div>
                        <p class="gmk-expert-card__badge"><?php echo esc_html( $expert['category'] ); ?></p>
                        <h3 class="gmk-expert-card__name"><?php echo esc_html( $expert['name'] ); ?></h3>
                        <p class="gmk-expert-card__title"><?php echo esc_html( $expert['title'] ); ?></p>
                    </div>
                </header>
                <div class="gmk-expert-card__meta">
                    <svg width="16" height="16" viewBox="0 0 24 24" aria-hidden="true">
                        <path d="M12 21s-6.5-6-6.5-10.5A6.5 6.5 0 0 1 18.5 10.5C18.5 15 12 21 12 21z" stroke="currentColor" stroke-width="1.5" fill="none" stroke-linecap="round" />
                    </svg>
                    <span><?php echo esc_html( $expert['location'] ); ?></span>
                </div>
                <ul class="gmk-expert-card__list">
                    <?php foreach ( array_slice( $expert['credentials'], 0, 4 ) as $item ) : ?>
                        <li><?php echo esc_html( $item ); ?></li>
                    <?php endforeach; ?>
                </ul>
                <footer class="gmk-expert-card__footer">
                    <button type="button" class="gmk-expert-card__action" data-modal-trigger>
                        Xem hồ sơ đầy đủ
                        <span aria-hidden="true">→</span>
                    </button>
                </footer>
            </article>
        <?php endforeach; ?>
    </section>

    <section class="gmk-expert-empty" id="gmk-expert-empty"<?php echo empty( $experts ) ? '' : ' hidden'; ?>>
        <div class="gmk-expert-empty__icon">🔍</div>
        <h4>Không tìm thấy chuyên gia phù hợp</h4>
        <p>Hãy thử lại với từ khóa khác hoặc bỏ lọc.</p>
        <button type="button" id="gmk-expert-reset">Đặt lại bộ lọc</button>
    </section>
</div>

<div class="gmk-expert-modal" id="gmk-expert-modal" aria-hidden="true">
    <div class="gmk-expert-modal__backdrop" data-modal-close></div>
    <div class="gmk-expert-modal__dialog" role="dialog" aria-modal="true" aria-labelledby="gmk-expert-modal-title">
        <button class="gmk-expert-modal__close" type="button" data-modal-close aria-label="Đóng">
            <span>&times;</span>
        </button>
        <div class="gmk-expert-modal__header">
            <img src="" alt="" id="gmk-expert-modal-avatar">
            <div>
                <p class="gmk-expert-card__badge" id="gmk-expert-modal-category"></p>
                <h2 id="gmk-expert-modal-title"></h2>
                <p id="gmk-expert-modal-role"></p>
                <div class="gmk-expert-modal__location">
                    <svg width="16" height="16" viewBox="0 0 24 24" aria-hidden="true">
                        <path d="M12 21s-6.5-6-6.5-10.5A6.5 6.5 0 0 1 18.5 10.5C18.5 15 12 21 12 21z" stroke="currentColor" stroke-width="1.5" fill="none" stroke-linecap="round" />
                    </svg>
                    <span id="gmk-expert-modal-location"></span>
                </div>
            </div>
        </div>
		<div class="gmk-expert-modal__body">
			<div class="gmk-expert-modal__pill">Hồ sơ nổi bật</div>
			<p class="gmk-expert-modal__summary" id="gmk-expert-modal-summary"></p>
			<div class="gmk-expert-modal__stats" id="gmk-expert-modal-stats"></div>
			<div class="gmk-expert-modal__list">
				<h3>Thành tựu & chứng chỉ</h3>
				<ol id="gmk-expert-modal-list"></ol>
			</div>
		</div>
    </div>
</div>

<style>
.gmk-experts-shell {
	padding-top: 4rem;
	padding-bottom: 4rem;
}
.gmk-expert-hero {
	text-align: center;
	max-width: 720px;
	margin: 0 auto 3rem;
}
.gmk-expert-pill {
	display: inline-flex;
	padding: 0.35rem 1.25rem;
	border-radius: 999px;
	border: 1px solid rgba(15, 23, 42, 0.08);
	font-size: 0.85rem;
	color: var(--gmk-muted);
	background: rgba(255, 255, 255, 0.75);
}
.gmk-expert-title {
	font-size: clamp(2.5rem, 6vw, 3.4rem);
	line-height: 1.2;
	font-weight: 700;
	margin: 1rem 0;
}
.gmk-expert-title span {
	color: #2563eb;
	display: inline-block;
}
.gmk-expert-sub {
	color: var(--gmk-muted);
}
.gmk-expert-filters {
	display: flex;
	flex-direction: column;
	gap: 1rem;
	margin-bottom: 2.5rem;
}
.gmk-expert-search {
	display: flex;
	align-items: center;
	gap: 0.75rem;
	background: #fff;
	border-radius: var(--gmk-radius);
	border: 1px solid var(--gmk-border);
	padding: 0.75rem 1rem;
	box-shadow: 0 15px 35px rgba(15, 23, 42, 0.06);
}
.gmk-expert-search input {
	border: none;
	width: 100%;
	font-size: 1rem;
	background: transparent;
	outline: none;
	color: var(--gmk-text);
}
.gmk-expert-chips {
	display: flex;
	flex-wrap: wrap;
	gap: 0.5rem;
}
.gmk-expert-chip {
	border: 1px solid var(--gmk-border);
	background: #fff;
	border-radius: 999px;
	padding: 0.4rem 1.1rem;
	font-size: 0.85rem;
	font-weight: 600;
	color: var(--gmk-muted);
	transition: all 0.2s ease;
	cursor: pointer;
}
.gmk-expert-chip.is-active {
	background: #0f172a;
	color: #fff;
	border-color: #0f172a;
	box-shadow: 0 10px 25px rgba(15, 23, 42, 0.2);
}
.gmk-expert-grid {
	display: grid;
	grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
	gap: 1.5rem;
}
.gmk-expert-card {
	background: #fff;
	border-radius: 20px;
	border: 1px solid var(--gmk-border);
	padding: 1.5rem;
	display: flex;
	flex-direction: column;
	gap: 1rem;
	box-shadow: 0 25px 40px rgba(15, 23, 42, 0.08);
	transition: transform 0.25s ease, box-shadow 0.25s ease;
}
.gmk-expert-card:hover {
	transform: translateY(-4px);
	box-shadow: 0 30px 60px rgba(15, 23, 42, 0.12);
}
.gmk-expert-card__header {
	display: flex;
	align-items: center;
	gap: 1rem;
}
.gmk-expert-card__media {
	width: 72px;
	height: 72px;
	border-radius: 18px;
	overflow: hidden;
	flex-shrink: 0;
	box-shadow: inset 0 0 0 1px rgba(255, 255, 255, 0.6);
}
.gmk-expert-card__media img {
	width: 100%;
	height: 100%;
	object-fit: cover;
}
.gmk-expert-card__badge {
	display: inline-flex;
	padding: 0.15rem 0.75rem;
	border-radius: 999px;
	background: rgba(37, 99, 235, 0.08);
	color: #2563eb;
	font-size: 0.75rem;
	font-weight: 600;
}
.gmk-expert-card__name {
	margin: 0.35rem 0 0.1rem;
	font-size: 1.35rem;
}
.gmk-expert-card__title {
	color: var(--gmk-muted);
	font-size: 0.95rem;
}
.gmk-expert-card__meta {
	display: flex;
	align-items: center;
	gap: 0.4rem;
	font-size: 0.9rem;
	color: var(--gmk-muted);
}
.gmk-expert-card__list {
	margin: 0;
	padding-left: 1.1rem;
	color: var(--gmk-text);
	display: flex;
	flex-direction: column;
	gap: 0.35rem;
	font-size: 0.95rem;
}
.gmk-expert-card__footer {
	border-top: 1px solid rgba(15, 23, 42, 0.08);
	padding-top: 1rem;
}
.gmk-expert-card__action {
	border: none;
	background: transparent;
	font-weight: 600;
	color: #0f172a;
	display: inline-flex;
	align-items: center;
	gap: 0.35rem;
	cursor: pointer;
	padding: 0;
}
.gmk-expert-card__action span {
	transition: transform 0.2s ease;
}
.gmk-expert-card__action:hover span {
	transform: translateX(4px);
}
.gmk-expert-empty {
	text-align: center;
	margin-top: 3rem;
	background: #fff;
	border-radius: 24px;
	padding: 3rem 2rem;
	border: 1px dashed var(--gmk-border);
}
.gmk-expert-empty__icon {
	font-size: 2rem;
	margin-bottom: 0.5rem;
}
.gmk-expert-empty button {
	margin-top: 1rem;
	padding: 0.6rem 1.4rem;
	border-radius: 999px;
	border: none;
	background: #2563eb;
	color: #fff;
	cursor: pointer;
	font-weight: 600;
}
.gmk-expert-modal {
	position: fixed;
	inset: 0;
	display: flex;
	align-items: center;
	justify-content: center;
	z-index: 50;
	opacity: 0;
	pointer-events: none;
	transition: opacity 0.25s ease;
}
.gmk-expert-modal.is-visible {
	opacity: 1;
	pointer-events: auto;
}
.gmk-expert-modal__backdrop {
	position: absolute;
	inset: 0;
	background: rgba(15, 23, 42, 0.65);
	backdrop-filter: blur(4px);
}
.gmk-expert-modal__dialog {
	position: relative;
	width: min(640px, 90%);
	max-height: 90vh;
	background: #fff;
	border-radius: 28px;
	padding: 2rem;
	box-shadow: 0 40px 80px rgba(15, 23, 42, 0.35);
	overflow-y: auto;
}
.gmk-expert-modal__close {
	position: absolute;
	top: 1rem;
	right: 1rem;
	border: none;
	background: #fff;
	width: 40px;
	height: 40px;
	border-radius: 50%;
	box-shadow: 0 10px 25px rgba(15, 23, 42, 0.18);
	cursor: pointer;
	font-size: 1.5rem;
	color: var(--gmk-muted);
}
.gmk-expert-modal__header {
	display: flex;
	gap: 1.25rem;
	align-items: center;
	padding-bottom: 1.5rem;
	border-bottom: 1px solid var(--gmk-border);
}
.gmk-expert-modal__header img {
	width: 96px;
	height: 96px;
	border-radius: 24px;
	object-fit: cover;
}
.gmk-expert-modal__location {
	margin-top: 0.35rem;
	display: inline-flex;
	align-items: center;
	gap: 0.35rem;
	font-size: 0.9rem;
	color: var(--gmk-muted);
}
.gmk-expert-modal__body {
	padding-top: 1.5rem;
}
.gmk-expert-modal__pill {
	display: inline-flex;
	align-items: center;
	gap: 0.35rem;
	padding: 0.35rem 1rem;
	border-radius: 999px;
	background: rgba(37, 99, 235, 0.08);
	color: #2563eb;
	font-weight: 600;
	font-size: 0.85rem;
}
.gmk-expert-modal__summary {
	margin: 0.75rem 0 1.25rem;
	color: var(--gmk-muted);
	font-size: 0.95rem;
}
.gmk-expert-modal__stats {
	display: grid;
	grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
	gap: 0.75rem;
	margin-bottom: 1.75rem;
}
.gmk-expert-modal__stats div {
	border: 1px solid var(--gmk-border);
	border-radius: 16px;
	padding: 0.9rem 1rem;
	background: #f8fafc;
	box-shadow: inset 0 1px 0 rgba(255,255,255,0.6);
}
.gmk-expert-modal__stats span {
	display: block;
	font-size: 0.8rem;
	text-transform: uppercase;
	letter-spacing: 0.08em;
	color: var(--gmk-muted);
	margin-bottom: 0.35rem;
}
.gmk-expert-modal__stats strong {
	font-size: 1.35rem;
	color: var(--gmk-text);
}
.gmk-expert-modal__list h3 {
	margin-bottom: 0.75rem;
}
.gmk-expert-modal__body h3 {
	margin-bottom: 1rem;
}
.gmk-expert-modal__body ol {
	padding-left: 1rem;
	display: flex;
	flex-direction: column;
	gap: 0.65rem;
}
.gmk-expert-modal__note {
	margin-top: 1.5rem;
	background: rgba(16, 185, 129, 0.08);
	border: 1px solid rgba(16, 185, 129, 0.3);
	border-radius: 16px;
	padding: 1rem 1.25rem;
	font-size: 0.95rem;
	color: #0f766e;
}
@media (max-width: 768px) {
	.gmk-expert-card__header {
		flex-direction: column;
		align-items: flex-start;
	}
	.gmk-expert-modal__dialog {
		padding: 1.5rem;
		border-radius: 24px;
	}
}
</style>

<script>
(function() {
    const shell = document.querySelector('.gmk-experts-shell');
    if (!shell) return;

    const searchInput = document.getElementById('gmk-expert-search');
    const chips = document.querySelectorAll('.gmk-expert-chip');
    const cards = document.querySelectorAll('.gmk-expert-card');
    const emptyState = document.getElementById('gmk-expert-empty');
    const resetBtn = document.getElementById('gmk-expert-reset');

    const modal = document.getElementById('gmk-expert-modal');
    const modalFields = {
        title: document.getElementById('gmk-expert-modal-title'),
        role: document.getElementById('gmk-expert-modal-role'),
        category: document.getElementById('gmk-expert-modal-category'),
        location: document.getElementById('gmk-expert-modal-location'),
        list: document.getElementById('gmk-expert-modal-list'),
        avatar: document.getElementById('gmk-expert-modal-avatar'),
		summary: document.getElementById('gmk-expert-modal-summary'),
		stats: document.getElementById('gmk-expert-modal-stats'),
    };

    let currentCategory = 'Tất cả';

    function applyFilter() {
        const keyword = searchInput.value.toLowerCase();
        let visibleCount = 0;

        cards.forEach((card) => {
            const data = JSON.parse(card.dataset.expert);
            const inCategory = currentCategory === 'Tất cả' || data.category === currentCategory;
            const inKeyword =
                data.name.toLowerCase().includes(keyword) ||
                data.title.toLowerCase().includes(keyword) ||
                data.credentials.join(' ').toLowerCase().includes(keyword);

            const shouldShow = inCategory && inKeyword;
            card.hidden = !shouldShow;
            if (shouldShow) visibleCount++;
        });

        emptyState.hidden = visibleCount !== 0;
    }

    chips.forEach((chip) => {
        chip.addEventListener('click', () => {
            chips.forEach((item) => {
                item.classList.remove('is-active');
                item.setAttribute('aria-selected', 'false');
            });
            chip.classList.add('is-active');
            chip.setAttribute('aria-selected', 'true');
            currentCategory = chip.dataset.category;
            applyFilter();
        });
    });

    searchInput.addEventListener('input', applyFilter);
    resetBtn.addEventListener('click', () => {
        searchInput.value = '';
        currentCategory = 'Tất cả';
        chips.forEach((chip, index) => {
            const active = index === 0;
            chip.classList.toggle('is-active', active);
            chip.setAttribute('aria-selected', active ? 'true' : 'false');
        });
        applyFilter();
    });

    document.addEventListener('click', (event) => {
        const trigger = event.target.closest('[data-modal-trigger]');
        if (!trigger) return;
        const card = trigger.closest('.gmk-expert-card');
        if (!card) return;
        const data = JSON.parse(card.dataset.expert);

        modalFields.title.textContent = data.name;
        modalFields.role.textContent = data.title;
        modalFields.category.textContent = data.category;
        modalFields.location.textContent = data.location;
        modalFields.avatar.src = data.avatar;
        modalFields.avatar.alt = data.name;
		modalFields.summary.textContent = `${data.category} · ${data.location}`;
		modalFields.stats.innerHTML = `
			<div>
				<span>Chứng chỉ</span>
				<strong>${data.credentials.length}</strong>
			</div>
			<div>
				<span>Lĩnh vực</span>
				<strong>${data.category}</strong>
			</div>
			<div>
				<span>Khu vực</span>
				<strong>${data.location}</strong>
			</div>
		`;
        modalFields.list.innerHTML = '';
        data.credentials.forEach((cred) => {
            const li = document.createElement('li');
            li.textContent = cred;
            modalFields.list.appendChild(li);
        });

        modal.classList.add('is-visible');
        modal.setAttribute('aria-hidden', 'false');
    });

    modal.addEventListener('click', (event) => {
        if (event.target.matches('[data-modal-close], [data-modal-close] *')) {
            modal.classList.remove('is-visible');
            modal.setAttribute('aria-hidden', 'true');
        }
    });

	applyFilter();
})();
</script>

<?php
get_footer();
