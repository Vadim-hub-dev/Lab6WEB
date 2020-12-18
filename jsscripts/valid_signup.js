var form = document.getElementById('newUserWithVal');

// коллекция полей формы из которой мы будем извлекать данные
var	elements = form.querySelectorAll('.form-control'),
	
	// объект кнопки, на который повесим обработчик события начала валидации формы
	btn	= document.getElementById('do_signup'),
	
	// регулярные выражения
    patternLogin = /^[a-zA-Z._0-9\-]+$/iu,
    patternEmail = /[0-9a-z_]+@[0-9a-z_^\.]+\.[a-z]{2,3}/i,
    patternNameFamily = /^[a-zA-Zа-яА-ЯёЁ]+$/iu,
    patternPassword = /^[0-9a-zA-Z._\-]+$/iu,

	
	// массив с сообщениями об ошибке
	errorMess	= [
		'Введите корректный логин', // [0]
		'Введите корректный email', // [1]
		'Введите корректное имя', // [2]
		'Введите корректную фамилию', // [3]
		'Введите корректный пароль' // [4]
	],
	// флаг ошибки валидации
	iserror	= true;

//событие начала валидации формы	
form.addEventListener('submit', validForm);

function getFormData(form) 
{
	// объект, куда будут записывать данные в формате 'имя_поля': 'значение'
	var controls = {};
	
	// если в 'form' полей не нашлось - вернём в функцию validForm пустое значение
	if (!form.elements) return '';
	
	// переберём в цикле поля формы, получим от каждого поля его значение, запишем полученные данные в 'controls'
	for (var i = 0, ln = form.elements.length; i < ln; i++) 
	{
		var element = form.elements[i];
		
		// имя поля находится в верхнем регистре (так их записывает JS при создании объекта 'form'), 
		//переведём имя элемента в нижний регистр и проверим, не является ли текущий элемент кнопкой, значение которой нас не интересует
		if (element.tagName.toLowerCase() != 'button') 
		{
			controls[element.id]= element.value;
		}
	} 
	return controls;
}

function getError(formVal, property) 
{
	// создаём литеральный объект validate, где каждому свойству литерального объекта соответствует анонимная функция, в которой
	// длина значения поля, у которого атрибут 'id' равен 'property', сравнивается с 0,
	// а само значение - с соответствующим выражением, если требуется
	// если сравнение истинно, то переменной error присваивается текст ошибки
	var error = '',
		validate = 
		{
			'loginReg': function() 
			{
				if (formVal.loginReg.length == 0 || patternLogin.test(formVal.loginReg) == false) 
				{
					error = errorMess[0];
				}
			},
			'emailReg': function() 
			{
				if (formVal.emailReg.length == 0 || patternEmail.test(formVal.emailReg) == false) 
				{
					error = errorMess[1];
				}
			},
			'nameReg': function() 
			{
				if (formVal.nameReg.length == 0 || patternNameFamily.test(formVal.nameReg) == false) 
				{
					error = errorMess[2];
				}
			},
			'familyReg': function() 
			{
				if (formVal.familyReg.length == 0 || patternNameFamily.test(formVal.familyReg) == false) 
				{
					error = errorMess[3];
				}
            },
            'passwordReg': function() 
			{
				if (formVal.passwordReg.length == 0 || patternPassword.test(formVal.passwordReg) == false) 
				{
					error = errorMess[4];
				}
            },
            'password_2Reg': function() 
			{
				if (formVal.password_2Reg.length == 0 || patternPassword.test(formVal.password_2Reg) == false) 
				{
					error = errorMess[4];
				}
            }
		};
 
	// если после вызова анонимной функции validate[property]() переменной error
	// было присвоено какое-то значение, то это значение и возвращаем,
	// в противном случае вернётся пустая строка, которая была присвоена изначально
	// перед объявлением литерального объекта validate
	validate[property]();
	return error;
}

function showError(property, error) 
{
	// получаем объект элемента, в который введены ошибочные данные
	var formElement = form.querySelector('[id=' + property + ']'),
	// с помощью DOM-навигации находим <span>, в который запишем текст ошибки
		errorBox	= formElement.nextElementSibling;
 
	// добавляем класс к <input>
	formElement.classList.add('form-control_error');
	// записываем текст ошибки в <span> 
	errorBox.innerHTML = error;
	// делаем <span> видимым
	errorBox.style.display = 'block';
}
 

function validForm(e) 
{
	e.preventDefault();
	var formVal = getFormData(form);
	var	error;
 
	for (var property in formVal) 
	{
		error = getError(formVal, property);
		if (error.length != 0) 
		{
			// устанавливаем флаг ошибки
			iserror = true;
			// вызываем функцию отображения текста ошибки
			showError(property, error);
		}else if(error.length == 0)
		{
			iserror = false;
		}
	}
 
	// если флаг ошибки сброшен (iserror == false), то отправляем данные
	if (iserror == false) 
	{
		form.submit();
	}
}

function cleanError(el) 
{
	var errorBox = el.nextElementSibling;
	el.classList.remove('form-control_error');
	errorBox.removeAttribute('style');
}

form.addEventListener('focus', function() 
{
	// находим элемент на который была нажата мышка 
	var el = document.activeElement;
	// если этот элемент не <button type="submit">,
	// вызываем функцию очистки <span class="error"> от текста ошибки
	if (el !== btn) cleanError(el);
}, true);