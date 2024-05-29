import { Controller } from "@hotwired/stimulus";
import * as Turbo from "@hotwired/turbo";
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
      Turbo.visit(this.urlValue)
    }
  }
}
