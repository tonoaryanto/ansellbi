.login-box-body, .register-box-body{background: transparent;padding: 20px;border-top: 0;color: #ddd;}
.form-control {
    background: #0000004a !important;
    color: #fff !important;
}
.form-control::placeholder {
  color: #f0f0f0;
}
.btn.focus, .btn:focus, .btn:hover {
    color: #a0a0a0 !important;
}

.animate-in {
    -webkit-animation: fadeIn .5s ease-in;
    animation: fadeIn .5s ease-in;
}

.animate-out {
	-webkit-animation: fadeOut .5s ease-out;
    animation: fadeOut .5s ease-out;
}

@-webkit-keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

@-webkit-keyframes fadeOut {
    from { opacity: 1; }
    to { opacity: 0; }
}

@keyframes fadeOut {
    from { opacity: 1; }
    to { opacity: 0; }
}