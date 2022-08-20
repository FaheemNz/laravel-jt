import Echo from "laravel-echo";

window.Echo = new Echo({
    broadcaster: "socket.io",
    host: window.location.hostname + ":" + window.laravel_echo_port,
    auth: {
        headers: {
            Authorization:
                "Bearer " +
                "eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiIxIiwianRpIjoiZjc0Y2MwZDU2ODcyNzk1YTdkZDgyY2EyMzQ3NDc0MzExOTY5MzgzNTY2MmRhODg3Nzc3Zjk4Y2E4MTU0YjczYmVlZjZmMGU5MDhmMThjYWUiLCJpYXQiOiIxNjA5NTcwMjI0Ljg3Njg2MiIsIm5iZiI6IjE2MDk1NzAyMjQuODc2ODY3IiwiZXhwIjoiMTY0MTEwNjIyNC43MDQ1ODEiLCJzdWIiOiIxNyIsInNjb3BlcyI6W119.HptvJ-_r-vtfJTnbv7eOM7U27ClrZyS-Ix5QkxN4-fLoGN_ME0YKSLzBCXfrKre4JWUBAsoq3rvraOnHMcApmaEx3nQjVJ-3m6Wzes22z5n93XQt7tjN4KGh2eKylZAFVc4BXiB_6HPwlKAvAP93t12n2my3PeimTuTCJzbtEVJ1nwdktiWgNdqIt4s2ynjMbV6K85mGX3nOwhTN0vANcmAET5QsKMRXYvgRkatmTyxJfKeoOsJKUeTC2CX_bkHByM_Z1qmvIe2U8PnwcewdR0Q_O7cmuY1EQ-uhdCNA4L6IS0cekqXWwK4XsEfL9BKVEKKPrAQXeFSWK_FSLc_BxcK8NFxcldfRL7IET704JPcHGQy_LbCOhUfNyAXB9sDMmPrL8F1og-gRKoKG6gPoBddZB_eR5v99r9tgCH14jt9VAE-r5hauo-Lzr4c3bKbCenJSkf8CzbjYcI4HABbo1Rze8JvFl1apxEhtnEK1Oja95Y-uLVehOtK5hwZvr8t5Z0bRqVCD5ULGTq6BG0T7FVJ7NT1Y3f_Y92O5dYYGxQEeNiowisoNOocdRFtCF1CHZuphdYijZsqlO7e4M69Ppp7KeCMIBzGpKT5D_0-L5-N_e63n0Wy506yRLO3ErMjJqiyxHnSt3eWLBq9-zQ3XTTWtq6s3Q5fV4V0OkBbnWBw"
        }
    }
});
