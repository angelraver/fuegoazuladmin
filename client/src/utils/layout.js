export default {
  hideNav (val) {
    document.getElementsByClassName('md-drawer')[0].style['z-index'] = val ? 5 : 6
  }
}
