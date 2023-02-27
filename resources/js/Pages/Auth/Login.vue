<template>
    <div>
        <Head title="Log in" />

        <jet-authentication-card>
            <template #logo>
                <jet-authentication-card-logo />
            </template>

            <jet-validation-errors class="mb-4" />

            <div v-if="status" class="mb-4 font-medium text-sm text-green-600">
                {{ status }}
            </div>

            <form @submit.prevent="submit">
                <div class="text-2xl text-center mb-10 mt-5">
                    Welcome back to <strong>{{$page?.props?.app?.name}}</strong>
                </div>
                <div v-if="$page?.props?.app?.canEmailLogin">
                    <div>
                        <jet-label for="email" value="Email" />
                        <jet-input
                            id="email"
                            v-model="form.email"
                            type="email"
                            class="mt-1 block w-full"
                            required
                            autofocus
                        />
                    </div>

                    <div class="mt-4">
                        <jet-label for="password" value="Password" />
                        <jet-input
                            id="password"
                            v-model="form.password"
                            type="password"
                            class="mt-1 block w-full"
                            required
                            autocomplete="current-password"
                        />
                    </div>

                    <div class="block mt-4">
                        <label class="flex items-center">
                            <jet-checkbox
                                v-model:checked="form.remember"
                                name="remember"
                            />
                            <span class="ml-2 text-sm text-gray-600"
                                >Remember me</span
                            >
                        </label>
                    </div>

                    <div class="flex items-center justify-end mt-4">
                        <Link
                            v-if="canResetPassword"
                            :href="route('password.request')"
                            class="
                                underline
                                text-sm text-gray-600
                                hover:text-gray-900
                            "
                        >
                            Forgot your password?
                        </Link>

                        <jet-button
                            class="social-btn bg-teal-500 text-base"
                            :class="{ 'opacity-25': form.processing }"
                            :disabled="form.processing"
                        >
                            Log in
                        </jet-button>
                    </div>
                    <div class="border-b border-gray-300 w-full my-5 text-center">or</div>
                </div>

                <!-- Login with GitLab -->
                <div class="flex items-center justify-end mt-4">
                    <a
                        class="btn social-btn"
                        href="/auth/login/gitlab"                        
                    >
                        Login with GitLab
                    </a>
                </div>

                <!-- Login with Single Sign On -->
                <div class="flex items-center justify-end mt-4">
                    <a
                        class="btn social-btn"
                        href="/auth/login/keycloak"
                    >
                        Login with Single-Sign-On
                    </a>
                </div>
            </form>
        </jet-authentication-card>
    </div>
</template>

<script>
import { defineComponent } from "vue";
import JetAuthenticationCard from "@/Jetstream/AuthenticationCard.vue";
import JetButton from "@/Jetstream/Button.vue";
import JetInput from "@/Jetstream/Input.vue";
import JetCheckbox from "@/Jetstream/Checkbox.vue";
import JetLabel from "@/Jetstream/Label.vue";
import JetValidationErrors from "@/Jetstream/ValidationErrors.vue";
import { Head, Link } from "@inertiajs/inertia-vue3";

export default defineComponent({
    components: {
        Head,
        JetAuthenticationCard,
        JetButton,
        JetInput,
        JetCheckbox,
        JetLabel,
        JetValidationErrors,
        Link,
    },

    props: {
        canResetPassword: Boolean,
        status: String,
    },

    data() {
        return {
            form: this.$inertia.form({
                email: "",
                password: "",
                remember: false,
            }),
        };
    },

    methods: {
        submit() {
            this.form
                .transform((data) => ({
                    ...data,
                    remember: this.form.remember ? "on" : "",
                }))
                .post(this.route("login"), {
                    onFinish: () => {
                        this.form.reset("password");
                        location.reload(true);
                        // this.$inertia.visit(this.route("dashboard"), {})
                    },
                });
        },
    },
});
</script>

<style lang="scss" scoped>
.social-btn {
    background: #3b5499;
    color: #ffffff;
    padding: 10px;
    width: 100%;
    text-align: center;
    display: block;
    border-radius: 3px;
}

</style>
