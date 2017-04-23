Date.prototype.shortFormat = function() {
  return this.getDate()  + "/" + (this.getMonth() + 1) + "/" + this.getFullYear();
}
