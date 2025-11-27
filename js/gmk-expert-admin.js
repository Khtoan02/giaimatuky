(function ($) {
	$(function () {
		let frame;

		$('.gmk-expert-avatar-select').on('click', function (event) {
			event.preventDefault();

			if (frame) {
				frame.open();
				return;
			}

			frame = wp.media({
				title: 'Chọn ảnh chuyên gia',
				button: { text: 'Sử dụng ảnh này' },
				multiple: false,
				library: { type: ['image'] },
			});

			frame.on('select', function () {
				const attachment = frame.state().get('selection').first().toJSON();
				$('#gmk_expert_avatar_id').val(attachment.id);
				$('.gmk-expert-avatar-preview').html(
					`<img src="${attachment.sizes?.medium?.url || attachment.url}" alt="" style="max-width:180px;border-radius:12px;">`
				);
				$('.gmk-expert-avatar-remove').removeClass('is-hidden');
			});

			frame.open();
		});

		$('.gmk-expert-avatar-remove').on('click', function (event) {
			event.preventDefault();
			$('#gmk_expert_avatar_id').val('');
			$('.gmk-expert-avatar-preview').empty();
			$(this).addClass('is-hidden');
		});
	});
})(jQuery);

