function createReview(review, reviewImages, mapPointId, userId) {
  const formData = new FormData();
  formData.set('rating', review.rating);
  formData.set('content', review.content);
  formData.set('isActive', +review.isActive);
  formData.set('mapPointId', mapPointId);
  formData.set('userId', userId);
  reviewImages.forEach((image) => {
    formData.append('reviewImages[]', image);
  });
  return window.$http({
    url: '/review/create',
    method: 'post',
    data: formData,
    headers: { 'Content-Type': 'multipart/form-data' },
  });
}

export default createReview;
