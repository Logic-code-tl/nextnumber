let status_list = false;

const telegram = window.Telegram.WebApp;
telegram.ready();

function voice_list() {
  const main_button = document.getElementById("full-number");
  const warn_div = document.getElementById("warn-full-number");

  if ($("#full-number").css("background-color") == "rgb(21, 41, 48)") {
    document.getElementById("normal-button").style.backgroundColor =
      "rgb(21, 41, 48)";
    document.querySelectorAll(".normal-list").forEach((box) => {
      box.style.left = "-100%";
    });
    if ($("#telegram-table").length > 0) {
      document.getElementById("telegram-table").style.right = "100%";
    }

    main_button.style.backgroundColor = "#484545";
    warn_div.style.left = "0%";
  } else {
    warn_div.style.left = "-100%";
    main_button.style.backgroundColor = "rgb(21, 41, 48)";
  }
}

function normal_list() {
  main_button = document.getElementById("normal-button");

  let normal_list = document.querySelectorAll(".normal-list");

  if ($("#normal-button").css("background-color") == "rgb(21, 41, 48)") {
    document.getElementById("full-number").style.backgroundColor =
      "rgb(21, 41, 48)";
    document.getElementById("warn-full-number").style.left = "-100%";

    main_button.style.backgroundColor = "#484545";

    normal_list.forEach((box) => {
      box.style.left = "36%";
    });
  } else {
    normal_list.forEach((box) => {
      box.style.left = "-100%";
      main_button.style.backgroundColor = "#152930";
    });
    document.getElementById("telegram-table").style.right = "100%";
  }
}

function open_telegram() {
  let table = document.getElementById("telegram-table");
  if (table.style.right == "0%") {
    table.style.right = "100%";
  } else {
    if (status_list == false) {
      fetch("/nextbot/webapp/?list=telegram")
        .then((response) => response.json())
        .then((data) => {
          for (let i = 0; i < data.length; i++) {
            let tr = document.createElement("tr");
            var amount = document.createElement("td");
            var country = document.createElement("td");
            var country_name = document.createElement("td");

            amount.innerHTML = data[i].cost;
            country.innerHTML = data[i].emoji;
            country_name.innerHTML = data[i].country_name + data[i].operator_id;

            country_name.style.width = "auto";
            country.style.width = "auto";

            let buy_button = document.createElement("div");
            buy_button.classList = "buy-button";
            buy_button.innerHTML = "خرید";
            2;

            tr.append(amount);
            tr.append(country_name);
            tr.append(country);
            tr.append(buy_button);

            document.getElementById("telegram-table").append(tr);
          }

          table.style.right = "0%";

          status_list = true;
        });
    } else {
      table.style.right = "0%";
    }
  }
}

function buy_number(status) {
  if (status == true) {
    fetch(`/nextbot/getnumber/`, {
      method: "POST",

      body: JSON.stringify({
        account: telegram.initData,
        service: "telegram",
        country: country_name,
        operator: operator,
      }),
    })
      .then((response) => response.json())
      .then((data) => {
        if (data.status == 0) {
          telegram.close();
        } else if (data.status == 1) {
          telegram.showAlert("موجودی ناکافی است");
        }
      });
  }
}

$(document).on("click", ".buy-button", function () {
  cell = $(this).prev().prev().text();
  cell = cell.split(/(\d+)/);

  country_name = cell[0];
  operator = cell[1];

  telegram.showConfirm("آیا از خرید شماره مطمعن هستید؟", buy_number);
});
