<template>
    <main class="main-container">
        <div class="top-container">
            <div class="top-image">
                <img src="/images/top.png" alt="">
            </div>
            <h1 class="top-title">時間割</h1>
            <div class="top-button-container">
                <router-link to="/register">
                    <button class="top-button top-signup">登録</button>
                </router-link>
                <router-link to="/login">
                    <button class="top-button top-login">ログイン</button>
                </router-link>
            </div>
        </div>
    </main>
</template>

<script>
export default {
    
}
</script>