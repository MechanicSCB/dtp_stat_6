function showPointContent(id, link) {
    document.getElementsByClassName('active')[0].className='';
    link.className='active';
    pointContentBlock = document.getElementById('point-content-block' );
    pointContentSrc = document.getElementById('content' + id);
    pointContentBlock.innerHTML = pointContentSrc.innerHTML
}
