import { metadata } from './metadata';

/**
 * main
 */
export

class main extends metadata {
    public path: string;
    public type: string;
    constructor(path:string,type:string) {
        super();
        this.path = path;
        this.type = type;
        this.completeForm();
        document.getElementById('metadata-form').addEventListener('submit', (e) => {
            e.preventDefault();
            if (this.readValues())
                this.save();
            else
                this.reportErrors();
        });
    }
    public readValues() {
        this.title = (<HTMLInputElement>document.getElementById('title')).value;
        this.author = (<HTMLSpanElement>document.getElementById('author')).innerHTML;
        this.description = (<HTMLInputElement>document.getElementById('description')).value;
        this.creation_date = (<HTMLSpanElement>document.getElementById('creation-date')).innerHTML;
        this.last_modified = (<HTMLSpanElement>document.getElementById('last-modified')).innerHTML;
        return this.validate();
    }
    public setDOMValues(){
        console.log(this.creation_date);
        (<HTMLInputElement>document.getElementById('title')).value = this.title || "";
        (<HTMLSpanElement>document.getElementById('author')).innerHTML = this.author || "";
        (<HTMLInputElement>document.getElementById('description')).value = this.description || "";
        (<HTMLSpanElement>document.getElementById('creation-date')).innerHTML = this.creation_date || "";
        (<HTMLSpanElement>document.getElementById('last-modified')).innerHTML = this.last_modified || "";
    }
    public completeForm(){ 
        var xhr = new XMLHttpRequest(),
            data = new FormData();
        data.append("path",this.path);
        data.append("action","findData");
        xhr.open('POST', "./helpers/helper.php");
        xhr.onload = () => {
            if (xhr.status === 200) {
                console.log(xhr.responseText);
                var response = JSON.parse(xhr.responseText);
                if(response.creation){
                    var date = new Date();
                    this.last_modified = response.last_modified;
                    this.creation_date = response.creation;
                    this.author = response.author;
                    this.title = response.title;
                    this.description = response.description;
                    this.author_id = response.author_id;
                    this.setDOMValues();
                }else{
                    this.last_modified = this.creation_date = this.getDefaultDate();
                    this.author = response.author;
                    this.author_id = response.author_id;
                    this.setDOMValues();
                }
            } else {
                console.log('Request failed. returned status id ' + xhr.status);
            }
        }
        xhr.send(data);
    }
    protected getDefaultDate(){
        var now = new Date();
        var day = ("0" + now.getDate()).slice(-2);
        var month = ("0" + (now.getMonth() + 1)).slice(-2);
        var today = now.getFullYear()+"-"+(month)+"-"+(day) ;
        return today;
    }
    public reportErrors() {
        let inputs = document.querySelectorAll('.form-control');
        for (var i = 0; i < inputs.length; i++) {
            var element = (<HTMLInputElement>inputs[i]);
            if (!element.value) {
                element.className = element.className.replace(" invalid", "");
                element.className += " invalid";
            }
        }
    }
    protected save() {
        var formData = new FormData();
        var xhr = new XMLHttpRequest();
        formData.append("action", "save");
        formData.append("path", this.path);
        formData.append("type", this.type);
        console.log(this.author_id);
        formData.append("author", this.author_id);
        formData.append("title", this.title);
        formData.append("description", this.description);
        formData.append("creation_date", this.creation_date);
        formData.append("last_modified", this.last_modified);
        xhr.open('POST', "./helpers/helper.php");
        xhr.onload = () => {
            if (xhr.status === 200) {
                console.log(xhr.responseText);
                window.location.href = "./helpers/helper.php?action=download&path="+encodeURI(xhr.responseText);
            } else {
                console.log('Request failed. returned status id ' + xhr.status);
            }
        }
        xhr.send(formData);
    }
}
