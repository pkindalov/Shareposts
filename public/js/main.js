let clickedOnPost = false;

function showHideCommentsOnPost(postId) {
	let commentBoxId = 'commentBoxPost' + postId;
	clickedOnPost = !clickedOnPost;
	let contEl = document.getElementById(commentBoxId);

	if (clickedOnPost) {
        contEl.classList.remove('d-none');
        document.getElementById('showHideCommentsBtn').textContent = 'Hide Comments';
	} else {
        contEl.classList.add('d-none');
        document.getElementById('showHideCommentsBtn').textContent = 'Show Comments';
	}
}
