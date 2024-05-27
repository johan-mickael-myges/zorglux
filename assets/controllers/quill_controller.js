import { Controller } from "@hotwired/stimulus";
import Quill from 'quill';

export default class extends Controller {
  static targets = ["editor"];
  static values = { options: Object };

  connect() {
    this.editor = new Quill(this.editorTarget, this.optionsValue);
  }
}
