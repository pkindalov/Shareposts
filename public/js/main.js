let clickedOnPost = false;

function showHideCommentsOnPost(postId) {
	let commentBoxId = 'commentBoxPost' + postId;
	clickedOnPost = !clickedOnPost;
	let contEl = document.getElementById(commentBoxId);

	if (clickedOnPost) {
		contEl.classList.remove('d-none');
	} else {
		contEl.classList.add('d-none');
	}
}
