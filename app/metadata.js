"use strict";
class metadata {
    constructor() {
    }
    validate() {
        if (this.author && this.title && this.description && this.creation_date)
            return true;
        return false;
    }
}
exports.metadata = metadata;
//# sourceMappingURL=metadata.js.map