export /**
 * metadata
 */
    class metadata {
    public author: string;
    public author_id: string;
    public title: string;
    public description: string;
    public creation_date: string;
    public last_modified: string
    constructor() {
    }
    protected validate() {
        if (this.author && this.title && this.description && this.creation_date)
            return true;
        return false;
    }
}