<?php
/**
 * Template Name: Form nhận câu hỏi
 * Description: Landing page thu thập thắc mắc của phụ huynh cho Giải Mã Tự Kỷ.
 *
 * @package giaimatuky
 */

get_header();

$videos_limit = absint(get_theme_mod('gmk_youtube_max_items', 8));
$videos_limit = $videos_limit > 0 ? $videos_limit : 8;
$latest_videos = function_exists('giaimatuky_get_youtube_videos')
    ? giaimatuky_get_youtube_videos(array(
        'max_results' => $videos_limit,
    ))
    : array();
$latest_videos_dynamic = !empty($latest_videos);

if (empty($latest_videos)) {
    $latest_videos = array(
        array(
            'id'    => 'OwHmXaBm6Os',
            'title' => 'Tự kỷ KHÔNG PHẢI là bệnh! Sự thật là gì?',
            'desc'  => 'Giải oan cho hội chứng tự kỷ và góc nhìn đúng đắn.',
            'thumbnail' => 'https://img.youtube.com/vi/OwHmXaBm6Os/hqdefault.jpg',
        ),
        array(
            'id'    => 'czbB8AQsBzU',
            'title' => 'Nhận biết sớm dấu hiệu tự kỷ ở trẻ',
            'desc'  => 'Các dấu hiệu cảnh báo sớm mà cha mẹ cần lưu ý.',
            'thumbnail' => 'https://img.youtube.com/vi/czbB8AQsBzU/hqdefault.jpg',
        ),
        array(
            'id'    => 'uYOI-jul0oI',
            'title' => 'Giải Mã Bệnh Tự Kỷ (Phần 2)',
            'desc'  => 'Hành trình chữa lành và thấu hiểu.',
            'thumbnail' => 'https://img.youtube.com/vi/uYOI-jul0oI/hqdefault.jpg',
        ),
        array(
            'id'    => 'Rjouq5I0mYA',
            'title' => 'Phát hiện sớm để đồng hành đúng cách',
            'desc'  => 'Tự kỷ không phải bản án, mà là một hành trình đặc biệt.',
            'thumbnail' => 'https://img.youtube.com/vi/Rjouq5I0mYA/hqdefault.jpg',
        ),
        array(
            'id'    => 'PVA9BDWcQ5U',
            'title' => 'Nguy cơ tự kỷ di truyền cần biết',
            'desc'  => 'Tìm hiểu về yếu tố gen và môi trường ảnh hưởng đến trẻ.',
            'thumbnail' => 'https://img.youtube.com/vi/PVA9BDWcQ5U/hqdefault.jpg',
        ),
    );
}

$show_youtube_notice = !$latest_videos_dynamic && current_user_can('manage_options');

$suggestions = array(
    array(
        'id'    => 'giao-tiep-ngon-ngu',
        'label' => 'Chậm nói / Giao tiếp kém',
        'text'  => 'Con tôi chậm nói, gọi không quay đầu. Làm sao để cải thiện kỹ năng tương tác xã hội cho bé?',
    ),
    array(
        'id'    => 'hanh-vi-cam-xuc',
        'label' => 'Hành vi lạ / Nhạy cảm',
        'text'  => 'Bé có các hành vi lặp lại, quá nhạy cảm với tiếng ồn hoặc đôi khi trở nên hung hăng.',
    ),
    array(
        'id'    => 'tieu-hoa-sinh-hoat',
        'label' => 'Tiêu hoá / Sinh hoạt',
        'text'  => 'Con rất kén ăn, hay bị táo bón hoặc tiêu chảy. Việc tập đi vệ sinh cũng rất khó khăn.',
    ),
    array(
        'id'    => 'can-thiep-giao-duc',
        'label' => 'Can thiệp / Trường lớp',
        'text'  => 'Nên chọn phương pháp nào (ABA, ESDM...)? Làm sao tìm trung tâm uy tín và hỗ trợ con ở trường?',
    ),
    array(
        'id'    => 'tuong-lai',
        'label' => 'Tương lai & Sống độc lập',
        'text'  => 'Liệu con tôi có thể sống độc lập khi trưởng thành? Con sẽ đi học, đi làm thế nào?',
    ),
    array(
        'id'    => 'ho-tro-gia-dinh',
        'label' => 'Áp lực cha mẹ / Cân bằng',
        'text'  => 'Tôi cảm thấy kiệt sức và căng thẳng. Cần lời khuyên để cân bằng cuộc sống khi đồng hành cùng con.',
    ),
);

$form_status = isset($_GET['form_status']) ? sanitize_text_field(wp_unslash($_GET['form_status'])) : '';
$form_error  = isset($_GET['form_error']) ? sanitize_text_field(wp_unslash($_GET['form_error'])) : '';
$prefill_topic = isset($_GET['topic']) ? sanitize_text_field(wp_unslash($_GET['topic'])) : 'giao-tiep-ngon-ngu';

$topics = array(
    'giao-tiep-ngon-ngu' => 'Giao tiếp & Ngôn ngữ',
    'hanh-vi-cam-xuc'    => 'Hành vi & Xử lý cảm giác',
    'tieu-hoa-sinh-hoat' => 'Tiêu hoá & Sinh hoạt',
    'nhan-thuc'          => 'Nhận thức & Tư duy',
    'can-thiep-giao-duc' => 'Can thiệp & Giáo dục',
    'tuong-lai'          => 'Tương lai & Khả năng sống độc lập',
    'nguyen-nhan'        => 'Nguyên nhân & Chẩn đoán',
    'ho-tro-gia-dinh'    => 'Hỗ trợ gia đình',
    'khac'               => 'Vấn đề khác',
);
?>

<main id="primary" class="site-main bg-slate-50 text-slate-800">
    <div class="min-h-screen font-sans">
        <section class="relative py-12 md:py-20 bg-gradient-to-b from-sky-50 to-white text-center">
            <div class="container mx-auto px-4 md:px-6">
                <div class="inline-flex items-center gap-2 px-4 py-1.5 bg-sky-100 text-sky-700 rounded-full text-sm font-semibold mb-4">
                    <i class="fa-solid fa-comments text-base"></i>
                    <span>Cùng nhau giải đáp thắc mắc</span>
                </div>
                <h1 class="text-3xl md:text-5xl lg:text-6xl font-extrabold text-slate-900 leading-tight mb-6">
                    Thấu hiểu để <span class="text-sky-600">yêu thương</span> đúng cách
                </h1>
                <p class="text-lg md:text-xl text-slate-600 max-w-2xl mx-auto leading-relaxed">
                    Chia sẻ câu chuyện của bạn, đội ngũ Giải Mã Tự Kỷ sẽ tổng hợp và giải đáp trong các video, bài viết và buổi tư vấn sắp tới.
                </p>
            </div>
        </section>

        <section id="gui-cau-hoi" class="pb-16 bg-white">
            <div class="container mx-auto px-4 md:px-6">
                <div class="max-w-6xl mx-auto bg-white rounded-3xl shadow-xl border border-slate-100 overflow-hidden">
                    <div class="grid md:grid-cols-12">
                        <div class="md:col-span-5 bg-sky-50 p-6 md:p-8 border-r border-sky-100">
                            <div class="flex items-center gap-2 mb-6">
                                <i class="fa-regular fa-lightbulb text-amber-500 text-xl"></i>
                                <h3 class="font-bold text-lg text-slate-800">Gợi ý vấn đề thường gặp</h3>
                            </div>
                            <div class="flex flex-col gap-3 h-[450px] overflow-y-auto pr-2">
                                <?php foreach ($suggestions as $suggestion) : ?>
                                    <button
                                        type="button"
                                        class="text-left p-3 rounded-xl bg-white border border-sky-200 hover:border-sky-500 hover:shadow-md transition-all group"
                                        data-suggestion="true"
                                        data-topic="<?php echo esc_attr($suggestion['id']); ?>"
                                        data-message="<?php echo esc_attr($suggestion['text']); ?>"
                                    >
                                        <span class="font-semibold text-sky-700 group-hover:text-sky-900 text-sm block mb-1">
                                            <?php echo esc_html($suggestion['label']); ?>
                                        </span>
                                        <span class="text-xs text-slate-500 line-clamp-2">
                                            “<?php echo esc_html($suggestion['text']); ?>”
                                        </span>
                                    </button>
                                <?php endforeach; ?>
                            </div>
                            <div class="mt-6 text-xs text-slate-500 leading-relaxed">
                                * Nội dung của bạn sẽ được ẩn danh hoàn toàn. Chúng tôi chỉ sử dụng để cảm nhận nhu cầu chung của cộng đồng.
                            </div>
                        </div>

                        <div class="md:col-span-7 p-6 md:p-8">
                            <div class="h-full flex flex-col">
                                <h3 class="text-2xl font-bold text-slate-900 mb-2">Gửi câu hỏi đến Giải Mã Tự Kỷ</h3>
                                <p class="text-slate-500 text-sm mb-6">Đội ngũ chuyên môn sẽ chọn lọc và phản hồi trong các nội dung định kỳ.</p>

                                <?php if ('success' === $form_status) : ?>
                                    <div class="flex flex-col items-center justify-center text-center space-y-4 py-10">
                                        <div class="w-16 h-16 bg-green-100 text-green-600 rounded-full flex items-center justify-center animate-bounce">
                                            <i class="fa-solid fa-paper-plane text-2xl"></i>
                                        </div>
                                        <h4 class="text-xl font-bold text-slate-900">Đã nhận được câu hỏi!</h4>
                                        <p class="text-slate-600 max-w-xs mx-auto">
                                            Cảm ơn bạn đã chia sẻ. Hãy theo dõi Fanpage/Youtube của Giải Mã Tự Kỷ để đón xem câu trả lời nhé!
                                        </p>
                                        <a href="<?php echo esc_url(remove_query_arg(array('form_status', 'form_error', 'topic'))); ?>" class="text-sky-600 font-semibold hover:underline">
                                            Gửi thêm câu hỏi khác
                                        </a>
                                    </div>
                                <?php else : ?>
                                    <?php
                                    $error_text = '';
                                    if ('error' === $form_status) {
                                        $messages = array(
                                            'invalid_nonce' => 'Phiên làm việc đã hết hạn, vui lòng thử lại.',
                                            'missing_fields' => 'Vui lòng chọn chủ đề và mô tả cụ thể câu hỏi.',
                                            'short_message' => 'Câu hỏi cần tối thiểu 20 ký tự để chúng tôi có thêm dữ kiện hỗ trợ.',
                                            'send_failed' => 'Hệ thống đang lỗi tạm thời, vui lòng thử lại sau ít phút.',
                                        );
                                        $error_text = isset($messages[ $form_error ]) ? $messages[ $form_error ] : 'Đã có lỗi xảy ra, vui lòng thử lại.';
                                    }
                                    ?>
                                    <?php if ($error_text) : ?>
                                        <div class="mb-4 rounded-2xl border border-rose-200 bg-rose-50 p-4 text-sm text-rose-700">
                                            <strong class="block font-semibold mb-1">Chưa gửi được rồi!</strong>
                                            <?php echo esc_html($error_text); ?>
                                        </div>
                                    <?php endif; ?>

                                    <form action="<?php echo esc_url(admin_url('admin-post.php')); ?>" method="post" class="space-y-4 flex-grow flex flex-col">
                                        <input type="hidden" name="action" value="gmk_submit_question">
                                        <?php wp_nonce_field('gmk_submit_question', 'gmk_question_nonce'); ?>
                                        <div>
                                            <label for="gmk-topic" class="block text-sm font-semibold text-slate-700 mb-2">Chủ đề liên quan</label>
                                            <select
                                                id="gmk-topic"
                                                name="topic"
                                                class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:border-sky-500 outline-none transition-all"
                                            >
                                                <?php foreach ($topics as $topic_slug => $topic_label) : ?>
                                                    <option value="<?php echo esc_attr($topic_slug); ?>" <?php selected($prefill_topic, $topic_slug); ?>>
                                                        <?php echo esc_html($topic_label); ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>

                                        <div class="flex-grow">
                                            <label for="gmk-message" class="block text-sm font-semibold text-slate-700 mb-2">Câu hỏi của bạn *</label>
                                            <textarea
                                                id="gmk-message"
                                                name="message"
                                                rows="7"
                                                required
                                                placeholder="Hãy mô tả chi tiết tình huống, độ tuổi của bé, điều bạn đang lo lắng..."
                                                class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:border-sky-500 outline-none transition-all resize-none text-slate-700"
                                            ></textarea>
                                            <p class="mt-2 text-xs text-slate-500">Ít nhất 20 ký tự, không cần cung cấp thông tin cá nhân.</p>
                                        </div>

                                        <button
                                            type="submit"
                                            class="mt-2 w-full py-3.5 bg-sky-600 hover:bg-sky-700 text-white font-bold rounded-xl shadow-lg transition-all flex items-center justify-center gap-2"
                                        >
                                            <i class="fa-solid fa-paper-plane text-sm"></i>
                                            <span>Gửi câu hỏi ngay</span>
                                        </button>
                                    </form>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="py-20 bg-[#FFF9E5]">
            <div class="container mx-auto px-4 md:px-6">
                <div class="text-center mb-12">
                    <h2 class="text-orange-600 font-bold tracking-widest text-sm uppercase mb-2">Đội ngũ chuyên gia</h2>
                    <h1 class="text-3xl md:text-4xl font-extrabold text-slate-900">Gặp gỡ Hội đồng Cố vấn</h1>
                </div>
                <div class="relative group px-4 max-w-6xl mx-auto">
                    <!-- Slider Track -->
                    <div class="overflow-hidden py-4">
                        <div id="gmk-expert-slider-track" class="flex transition-transform duration-500 ease-out will-change-transform">
                            <?php
                            // GIẢ LẬP DỮ LIỆU CHUYÊN GIA
                            $EXPERTS = [
                                [
                                    "id" => 1,
                                    "image" => "https://giaimatuky.quest/wp-content/uploads/2025/11/1.jpg",
                                ],
                                [
                                    "id" => 2,
                                    "image" => "https://giaimatuky.quest/wp-content/uploads/2025/11/10.jpg",
                                ],
                                [
                                    "id" => 3,
                                    "image" => "https://giaimatuky.quest/wp-content/uploads/2025/11/11.jpg",
                                ],
                                [
                                    "id" => 4,
                                    "image" => "https://giaimatuky.quest/wp-content/uploads/2025/11/15.jpg",
                                ],
                                [
                                    "id" => 5,
                                    "image" => "https://giaimatuky.quest/wp-content/uploads/2025/11/16.jpg",
                                ],
                                [
                                    "id" => 6,
                                    "image" => "https://giaimatuky.quest/wp-content/uploads/2025/11/17.jpg",
                                ],
                                [
                                    "id" => 7,
                                    "image" => "https://giaimatuky.quest/wp-content/uploads/2025/11/Eva.jpg",
                                ],
                            ];
                            foreach ($EXPERTS as $expert) : ?>
                                <div class="flex-shrink-0 px-2 sm:px-3 md:px-4 slider-expert-card">
                                    <div class="group/card cursor-pointer relative transition-transform duration-300 hover:-translate-y-2">
                                        <div class="w-full aspect-square rounded-2xl overflow-hidden shadow-lg border-2 border-white bg-white">
                                            <img 
                                                src="<?php echo esc_url($expert['image']); ?>"
                                                alt="Expert Slide"
                                                class="w-full h-full object-cover transition-transform duration-500 group-hover/card:scale-105"
                                            />
                                            <div class="absolute inset-0 bg-black/0 group-hover/card:bg-white/10 transition-colors duration-300 pointer-events-none"></div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <!-- Navigation Buttons -->
                    <button 
                        type="button"
                        id="gmk-expert-slider-prev"
                        class="absolute top-1/2 -left-2 md:-left-6 -translate-y-1/2 w-10 h-10 md:w-12 md:h-12 bg-white hover:bg-orange-50 text-slate-800 hover:text-orange-600 rounded-full shadow-lg flex items-center justify-center transition-all hover:scale-110 z-20 border border-slate-200"
                        aria-label="Chuyên gia trước"
                    >
                        <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"></polyline></svg>
                    </button>
                    <button 
                        type="button"
                        id="gmk-expert-slider-next"
                        class="absolute top-1/2 -right-2 md:-right-6 -translate-y-1/2 w-10 h-10 md:w-12 md:h-12 bg-white hover:bg-orange-50 text-slate-800 hover:text-orange-600 rounded-full shadow-lg flex items-center justify-center transition-all hover:scale-110 z-20 border border-slate-200"
                        aria-label="Chuyên gia tiếp theo"
                    >
                        <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"></polyline></svg>
                    </button>
                    <!-- Pagination Dots -->
                    <div class="flex justify-center mt-8 gap-2" id="gmk-expert-slider-dots">
                        <!-- Dots will render by JS -->
                    </div>
                </div>
            </div>
            <script>
            (function() {
                // --- Data --- //
                const EXPERTS = <?php echo json_encode($EXPERTS); ?>;
                // Elements
                const track = document.getElementById('gmk-expert-slider-track');
                const prevBtn = document.getElementById('gmk-expert-slider-prev');
                const nextBtn = document.getElementById('gmk-expert-slider-next');
                const dotsContainer = document.getElementById('gmk-expert-slider-dots');

                // State
                let currentIndex = 0;
                let itemsPerScreen = 4;

                function updateItemsPerScreen() {
                    if (window.innerWidth < 640) itemsPerScreen = 1;
                    else if (window.innerWidth < 1024) itemsPerScreen = 2;
                    else if (window.innerWidth < 1280) itemsPerScreen = 3;
                    else itemsPerScreen = 4;
                }

                function getMaxIndex() {
                    return Math.max(0, EXPERTS.length - itemsPerScreen);
                }

                function updateSlider() {
                    // Slide
                    track.style.transform = `translateX(-${currentIndex * (100/itemsPerScreen)}%)`;
                    // Responsive width for slides
                    Array.from(track.children).forEach(el => {
                        el.style.width = (100/itemsPerScreen) + "%";
                    });

                    // Render dots
                    if(dotsContainer){
                        dotsContainer.innerHTML = "";
                        let numDots = Math.max(0, EXPERTS.length - itemsPerScreen + 1);
                        for(let i = 0; i < numDots; i++) {
                            const btn = document.createElement('button');
                            btn.type = "button";
                            btn.className = "h-2 rounded-full transition-all duration-300 " + (currentIndex === i ? "w-8 bg-orange-600" : "w-2 bg-slate-300 hover:bg-slate-400");
                            btn.setAttribute("aria-label", "Slide " + (i+1));
                            if(currentIndex === i) btn.setAttribute("aria-current", "true");
                            btn.addEventListener('click', function() {
                                currentIndex = i;
                                updateSlider();
                            });
                            dotsContainer.appendChild(btn);
                        }
                    }
                }

                function nextSlide() {
                    const maxIndex = getMaxIndex();
                    currentIndex = (currentIndex >= maxIndex ? 0 : currentIndex + 1);
                    updateSlider();
                }

                function prevSlide() {
                    const maxIndex = getMaxIndex();
                    currentIndex = (currentIndex <= 0 ? maxIndex : currentIndex - 1);
                    updateSlider();
                }

                // Init
                function handleResize() {
                    updateItemsPerScreen();
                    // Clamp current index
                    if(currentIndex > getMaxIndex()) currentIndex = getMaxIndex();
                    updateSlider();
                }
                window.addEventListener('resize', handleResize);

                prevBtn && prevBtn.addEventListener('click', prevSlide);
                nextBtn && nextBtn.addEventListener('click', nextSlide);

                // Touch support (swipe)
                let startX = 0, isDragging = false;
                if(track){
                    track.addEventListener('touchstart', function(e){
                        isDragging = true;
                        startX = e.touches[0].clientX;
                    });
                    track.addEventListener('touchend', function(e){
                        if(!isDragging) return;
                        let endX = e.changedTouches[0].clientX;
                        let deltaX = endX - startX;
                        if (deltaX > 40) prevSlide();
                        if (deltaX < -40) nextSlide();
                        isDragging = false;
                    });
                }

                // Kickstart
                handleResize();

            })();
            </script>
        </section>

        <section id="videos" class="py-20 bg-slate-50">
            <div class="container mx-auto px-4 md:px-6 space-y-8">
                <?php if ($show_youtube_notice) : ?>
                    <div class="rounded-2xl border border-amber-200 bg-amber-50 p-4 text-amber-800 text-sm">
                        <strong>Cần cấu hình kênh YouTube:</strong>
                        Vào Customizer → “YouTube channel” để nhập Channel ID, hệ thống sẽ tự đồng bộ video mới nhất qua RSS feed.
                    </div>
                <?php endif; ?>
                <div class="text-center max-w-2xl mx-auto">
                    <h3 class="text-3xl font-bold text-slate-900">Video mới nhất từ kênh</h3>
                    <p class="text-slate-500 mt-3">Những nội dung được cập nhật thường xuyên để giải đáp thắc mắc của cộng đồng phụ huynh.</p>
                </div>
                <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                    <?php foreach ($latest_videos as $video) : ?>
                        <?php
                        $video_id    = isset($video['id']) ? $video['id'] : '';
                        $video_title = isset($video['title']) ? $video['title'] : '';
                        if ('' === $video_id || '' === $video_title) {
                            continue;
                        }
                        $video_url  = isset($video['url']) ? $video['url'] : sprintf('https://www.youtube.com/watch?v=%s', $video_id);
                        $video_desc = isset($video['desc']) ? $video['desc'] : '';
                        $video_thumb = !empty($video['thumbnail'])
                            ? $video['thumbnail']
                            : sprintf('https://img.youtube.com/vi/%s/hqdefault.jpg', $video_id);
                        ?>
                        <article class="flex flex-col bg-white rounded-2xl shadow-md hover:shadow-xl transition duration-300 group overflow-hidden">
                            <div class="aspect-video w-full bg-slate-900 overflow-hidden">
                                <img
                                    src="<?php echo esc_url($video_thumb); ?>"
                                    alt="<?php echo esc_attr($video_title); ?>"
                                    class="w-full h-full object-cover opacity-90 group-hover:opacity-100 transition"
                                    loading="lazy"
                                >
                            </div>
                            <div class="p-5 flex flex-col flex-1">
                                <h4 class="font-bold text-slate-900 text-lg leading-tight line-clamp-2 mb-2 group-hover:text-sky-600 transition-colors">
                                    <?php echo esc_html($video_title); ?>
                                </h4>
                                <p class="text-sm text-slate-500 line-clamp-3 flex-1">
                                    <?php echo esc_html($video_desc); ?>
                                </p>
                                <a
                                    href="<?php echo esc_url($video_url); ?>"
                                    class="mt-4 inline-flex items-center gap-2 text-xs font-semibold text-sky-700 uppercase tracking-wider"
                                    target="_blank"
                                    rel="noreferrer"
                                >
                                    Xem ngay
                                    <i class="fa-solid fa-arrow-up-right-from-square text-[10px]"></i>
                                </a>
                            </div>
                        </article>
                    <?php endforeach; ?>
                </div>
                <div class="text-center">
                    <a
                        href="https://www.youtube.com/channel/UC8RRqVV1gZyor3hPvyxjjNw"
                        target="_blank"
                        rel="noreferrer"
                        class="inline-flex items-center gap-2 px-5 py-3 rounded-full border border-slate-200 text-slate-600 hover:border-sky-500 hover:text-sky-600 transition font-semibold text-sm"
                    >
                        <i class="fa-brands fa-youtube text-red-600"></i>
                        Xem thêm trên kênh YouTube
                    </a>
                </div>
            </div>
        </section>
    </div>
</main>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const suggestionButtons = document.querySelectorAll('[data-suggestion]');
    const topicSelect = document.getElementById('gmk-topic');
    const messageTextarea = document.getElementById('gmk-message');

    suggestionButtons.forEach(function (button) {
        button.addEventListener('click', function () {
            if (topicSelect) {
                topicSelect.value = button.getAttribute('data-topic');
            }
            if (messageTextarea) {
                messageTextarea.value = button.getAttribute('data-message');
                messageTextarea.focus();
            }
            button.classList.add('ring-2', 'ring-sky-500');
            setTimeout(function () {
                button.classList.remove('ring-2', 'ring-sky-500');
            }, 400);
        });
    });
});
</script>

<?php
get_footer();