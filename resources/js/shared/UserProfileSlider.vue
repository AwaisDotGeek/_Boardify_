<template>
    <div class="user-profile-slider mt-2" :class="{ 'userProfileActive': isUserProfileCardActive }">
        <div class="user-profile-slider-content-container">
            <div class="slider-user-details-container">
                <div class="slider-user-photo-container">
                    <img src="../assets/logo.png" alt="">
                </div>
                <div class="slider-user-details">
                    <h3 v-if="user">{{ user.username }}</h3>
                </div>
            </div>

            <div class="slider-tabs-container" :class="{'opacityZero': !isUserProfileCardActive }">
                <div class="tab-ions-container">
                    <i @click="handleSliderTabClick('tab1')" class="fa-solid fa-gamepad" :class="{'active-icon': activeSliderTab === 'tab1'}"></i>
                    <i @click="handleSliderTabClick('tab2')" class="fa-solid fa-user-group" :class="{ 'active-icon': activeSliderTab === 'tab2' }"></i>
                    <i @click="handleSliderTabClick('tab3')" class="fa-solid fa-bell" :class="{ 'active-icon': activeSliderTab === 'tab3' }"></i>
                    <i @click="handleSliderTabClick('tab4')" class="fa-solid fa-gear" :class="{ 'active-icon': activeSliderTab === 'tab4' }"></i>
                </div>
                <div class="tab-body-container tab-1" :class="{ 'active-tab': activeSliderTab === 'tab1' }">
                    <div class="tab-content-container">
                        <h3>Games History</h3>
                    </div>
                </div>
                <div class="tab-body-container tab-2" :class="{ 'active-tab': activeSliderTab === 'tab2' }">
                    <div class="tab-content-container">
                        <h3>Friends</h3>
                    </div>
                </div>
                <div class="tab-body-container tab-3" :class="{ 'active-tab': activeSliderTab === 'tab3' }">
                    <div class="tab-content-container">
                        <h3>Notifications</h3>
                    </div>
                </div>
                <div class="tab-body-container tab-4" :class="{ 'active-tab': activeSliderTab === 'tab4' }">
                    <div class="tab-content-container">
                        <h3>Settings</h3>
                    </div>
                </div>
            </div>

            <!--
            <div class="slider-links-container">
                <h3>My Profile</h3>
                <h3>Settings</h3>
                <h3>FAQs</h3>
                <h3>Help / Support</h3>
                <h3>Logout</h3>
            </div>
            -->
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            isUserProfileCardActive: Boolean,
        },
        computed: {
          user() {
              return this.$page.props.auth.user;
          }
        },
        data() {
            return {
                activeSliderTab: 'tab1',
            }
        },
        methods: {
            handleSliderTabClick(clickedTab) {
                if (clickedTab !== this.activeSliderTab) {
                    this.activeSliderTab = clickedTab;
                }
            },
        },
    }
</script>

<style scoped>
    .user-profile-slider {
        height: 0;
        width: 0;
        background: #333738;
        z-index: 3;
        border-radius: 0 0 0 15px;
        color: #fff;
        padding: 0;

        position: absolute;
        top: 75px;
        right: 0;

        transition: width 0.2s ease, height 0.4s ease-in-out, box-shadow 1.5s ease-in-out;
    }

    .userProfileActive {
        width: 380px;
        height: 600px;
        padding: 20px 10px 10px 10px;
    }

    .user-profile-slider-content-container {
        height: 100%;
        display: flex;
        flex-direction: column;
        gap: 20px;
    }
    .slider-user-details-container {
        display: flex;
        gap: 10px;
    }

    .slider-user-photo-container {
        width: 85px;
        height: 85px;
    }

    .slider-user-photo-container > img {
        border-radius: 50%;
    }

    .slider-user-details {
        display: flex;
        align-items: center;
    }


    /* Slider tabs */
    .slider-tabs-container {
        flex-grow: 1;
        opacity: 1;

        display: flex;
        flex-direction: column;


        transition: all 1.5s ease;
    }

    .opacityZero {
        opacity: 0;
    }

    .tab-ions-container {
        display: flex;
    }

    i {
        color: #ccc;
        flex-grow: 1;
        text-align: center;
        padding: 10px;
        border-radius: 8px 8px 0 0;
        font-size: 20px;
    }

    i:hover {
        color: #fff;
        cursor: pointer;
    }

    .active-icon {
        color: #fff;
        background: #212b2a;
    }

    .tab-body-container {
        flex-grow: 1;
        padding: 10px;
        border-radius: 0 0 10px 10px;
        background: #212b2a;
        display: none;
    }

    .active-tab {
        display: block;
    }

    /* ----------------------- */
    /* ----------------------- */
    /* ----------------------- */

    h3 {
        letter-spacing: 0.3px;
    }
</style>
