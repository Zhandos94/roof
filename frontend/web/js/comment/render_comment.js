var renderComment = function  () {
	var _renderComment, _itemCommentCreateEl,
		_nicknameAndDateCreateEl, _buttonReplyCreateEl,
		_likeVisible, _renderError;

	/**
	 * [_renderComment description]
	 * @protected
	 * @param  {Object} item
	 * @return {void}
	 */
	_renderComment = function (item) {
		var comment, commentItemDiv, nicknamem, buttonReply, likes, parentEl, marginLeft;

		commentItemDiv = _itemCommentCreateEl(item.id);
		nickname = _nicknameAndDateCreateEl(item.nickname, item.created_at);
		buttonReply = _buttonReplyCreateEl(item.id);
		likes = _likeVisible(item.like, item.diss_like);
		item.blocked ? comment = /*_t('Oops sorry!')*/ 'Oops' : comment = item.body;

		commentItemDiv.append('<div class="comment-header">' + nickname + '</div>')
			.append('<div class="comment-body">' + comment + '</div>')
			.append(buttonReply)
			.append(likes);

		if (group_id != item.group_id) {
			$(commentItemDiv).addClass('child-comment');
			parentEl = $('[data-comment-id = ' + item.parent_id + ']');
			if (parentEl.hasClass('child-comment')) {
				marginLeft =  parseInt(parentEl.css('margin-left')) + parseInt($('.child-comment:first').css('margin-left'));
				commentItemDiv.css('margin-left', marginLeft + 'px');
			}
			parentEl.after(commentItemDiv);
		} else {
			$('#comments').append(commentItemDiv);
		}
	}

	_itemCommentCreateEl = function(id) {
		var id = id || null;
		return $('<div class="item-comment" data-comment-id="' + id + '"></div>');
	}

	_nicknameAndDateCreateEl = function(nickname, createDate) {
		var createDate = new Date(createDate);

		var	month = createDate.getMonth() + 1,
			day = createDate.getDate(),
			year = createDate.getFullYear(),
			hours = createDate.getHours(),
			minutes = createDate.getMinutes(),
			seconds = createDate.getSeconds()
		;
		minutes = minutes < 10 ? '0' + minutes : minutes;
		var full_date ='<span class="created-date">' + hours + ':' + minutes + ' ' + day + '.' + month + '.' + year + '</span>';

		return '<strong class="nickname">' + nickname + '</strong>' + full_date;
	}

	// _t("Ответить")
	_buttonReplyCreateEl = function(id) {
		return $('<a href="#" class="button-comment button-reply"'
			+ ' data-subject-id="' + id
			+ '" data-group-id="' + comment_group_id
			+ '" data-global-group-id="' + group_id
			+ '">Ответить</a>');
	}

	_likeVisible = function(likeComment, dissLikeComment) {
		var likes, like, dissLike, result = null;
		if (!isGuest) {
			likes = $('<div class="likes"></div>');
			like = '<div class="like-count">'
				+ '<span class="like-count-up">' + likeComment + '</span>'
				+ '<span class="fa fa-thumbs-o-up like" data-like-status="like-up"></span>'
				+ '</div>';
			dissLike = '<div class="like-count">'
				+'<span class="like-count-down">' + dissLikeComment + '</span>'
				+'<span class="fa fa-thumbs-o-down like" data-like-status="like-down"></span>'
				+'</div>';
			result = likes.append(like).append(dissLike);
		}
		return result;
	}

	_renderError = function (item) {
		var commentItemDiv;
		commentItemDiv = _itemCommentCreateEl();
		$(commentItemDiv).addClass('no-moderation');
		commentItemDiv.append('Ваш комментарий скрыт по причине не соответсвия с правилами Портала');
		if (group_id != item.group_id) {
			$(commentItemDiv).addClass('child-comment');
			parentEl = $('[data-comment-id = ' + item.parent_id + ']');
			if (parentEl.hasClass('child-comment')) {
				marginLeft =  parseInt(parentEl.css('margin-left')) + parseInt($('.child-comment:first').css('margin-left'));
				commentItemDiv.css('margin-left', marginLeft + 'px');
			}
			parentEl.after(commentItemDiv);
		} else {
			$('#comments').append(commentItemDiv);
		}
	}

	return {
		/**
		 * [renderComments description]
		 * @public
		 * @param  {Array} comments
		 * @return {void}
		 */
		renderComments: function (comments) {
			comments.forEach(function (item) {
				_renderComment(item);
			});
		},

		renderComment: _renderComment,
		renderError: _renderError
	};
};
