import { Controller } from "@hotwired/stimulus";
import * as Turbo from "@hotwired/turbo";
export default class extends Controller {
  static targets = ["elements"];
  static values = {
    url: String,
    target: String,
  }

  connect() {
    // this.elementsTarget.addEventListener("click", this.click.bind(this));
  }

  visit() {
    if (this.urlValue) {
      Turbo.visit(this.urlValue)
    }
  }
}
