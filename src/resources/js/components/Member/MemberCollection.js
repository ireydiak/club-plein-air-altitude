import {MemberModel} from "./MemberModel";
import {Collection} from "vue-mc";

export class MemberCollection extends Collection {

    options() {
        return {
            model: MemberModel,
        }
    };

    routes() {
        return {
            fetch: '/members'
        }
    };

}
