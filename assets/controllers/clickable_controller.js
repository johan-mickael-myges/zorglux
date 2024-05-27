import { Controller } from "@hotwired/stimulus";

export default class extends Controller {
  static targets = ["element"];
  static values = {
    url: String,
    target: String,
  }

  connect() {
    this.elementTarget.addEventListener("click", this.click.bind(this));
  }

  click() {
    if (this.urlValue) {
      window.open(this.urlValue, this.targetValue || "_self");
    }
  }
}
