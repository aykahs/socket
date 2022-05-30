<!DOCTYPE html>
<html>

<head>
    <title>Socket.IO chat</title>
    <style>
        body {
            margin: 0;
            padding-bottom: 3rem;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
        }

        #form {
            background: rgba(0, 0, 0, 0.15);
            padding: 0.25rem;
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            display: flex;
            height: 3rem;
            box-sizing: border-box;
            backdrop-filter: blur(10px);
        }

        #input {
            border: none;
            padding: 0 1rem;
            flex-grow: 1;
            border-radius: 2rem;
            margin: 0.25rem;
        }

        #input:focus {
            outline: none;
        }

        #form>button {
            background: #333;
            border: none;
            padding: 0 1rem;
            margin: 0.25rem;
            border-radius: 3px;
            outline: none;
            color: #fff;
        }

        #messages {
            list-style-type: none;
            margin: 0;
            padding: 0;
        }

        #messages>li {
            padding: 0.5rem 1rem;
        }

        #messages>li:nth-child(odd) {
            background: #efefef;
        }

    </style>
</head>

<body>
    <ul id="users"></ul>
    <ul id="messages"></ul>
    <form id="form" action="">
        <input id="input" autocomplete="off" /><button>Send</button>
    </form>
</body>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>

    <script src="https://cdn.socket.io/4.5.0/socket.io.min.js"
        integrity="sha384-7EyYLQZgWBi67fBtVxw60/OWl1kjsfrPFcaU0pp0nAh+i8FD068QogUvg85Ewy1k" crossorigin="anonymous">
    </script>
    <script>
        var socket = io('http://localhost:4000');

        var messages = document.getElementById('messages');
        var users_ = document.getElementById('users');

        var form = document.getElementById('form');
        var input = document.getElementById('input');
        const username = "{{ auth::user()->name }}";
        var user_id = '';
        socket.auth = {
            username
        };
        socket.connect();
        socket.on("connect_error", (err) => {
            if (err.message === "invalid username") {
                alert("invalid username");
            }
        });

        function sentToUser(u) {
            user_id = u
            console.log(u)
            localStorage.setItem("lastname", "Smith");

        }
        socket.on("users", (users) => {
            users.forEach((u) => {
                console.log(u);
                // let i = document.createElement('li');
                // console.log(i)
                // i.textContent = u.username;
                // i.onclick = function() {
                //     user_id = u.userID
                // }
                // users_.appendChild(i);
                // console.log(users_)
                $("#users").append(``);

                $("#users").append(`<li onclick="sentToUser('${u.userID}')">${u.username}</li>`);


            });
        });

        socket.on("user connected", (user) => {
            $("#users").append(``);

            $("#users").append(`<li onclick="sentToUser('${user.userID}')">${user.username}</li>`);

        });
        // $('form').on('submit', function(e) {

        form.addEventListener('submit', function(e) {
            e.preventDefault();
            console.log(user_id)
            if (input.value) {
                let content = input.value;
                socket.emit('chat message', {
                    content,
                    to: user_id,
                });
                input.value = '';
            }
        });
        socket.on('chat message', ({
            content,
            from
        }) => {
            console.log(content, from)

            $("#messages").append(`<li>${content}</li>`);

            window.scrollTo(0, document.body.scrollHeight);
        });
    </script>

</html>
