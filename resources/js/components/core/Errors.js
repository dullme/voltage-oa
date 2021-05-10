class Errors{
    constructor() {
        this.errors = {};
    }

    has(field) {
        return this.errors.hasOwnProperty(field);
    }

    any() {
        return Object.keys(this.errors).length > 0;
    }

    get(field) {
        if(this.errors[field]) {
            return this.errors[field][0];
        }
    }

    getFields(fields) {
        let self = this;
        fields.forEach(function (field) {
            if(self.errors[field]) errors[field] = self.errors[field] ;
        });
        return errors;
    }

    set(field, value) {
        if(this.errors[field]) {
            this.errors[field] = value;
        }
    }

    record(errors) {
        this.errors = errors;
    }

    clear(field) {
        if(field) {
            delete this.errors[field];
            return;
        }

        this.errors = {};
    }

}

export default Errors;