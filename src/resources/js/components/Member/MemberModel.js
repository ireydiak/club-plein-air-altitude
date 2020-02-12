import {Model} from 'vue-mc';
import {string, email, required} from "vue-mc/validation";

export class MemberModel extends Model {

    options() {
        return {
            identifier: 'member_id',
        }
    };

    defaults() {
        return {
            memberId: null,
            email: null,
            firstName: null,
            lastName: null,
            password: null,
            facebookLink: null,
            cip: null,
            role: 'Membre',
            phone: null,
            phoneRegion: 'CA'
        }
    };

    availableRoles() {
        return ['Membre', 'Permanent', 'Admin'];
    };

    routes() {
        return {
            fetch: '/member/{id}',
            save: '/members'
        }
    };

    validation() {
        return {
            firstName: string.and(required),
            lastName: string.and(required),
            email: email,
        }
    };
}
