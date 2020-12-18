var form = document.getElementById('newSotrudWithVal');

// коллекция полей формы из которой мы будем извлекать данные
var	elements = form.querySelectorAll('.form-control'),
	
	// объект кнопки, на который повесим обработчик события начала валидации формы
	btn	= document.getElementById('do_incert'),
	
	// регулярные выражения
	patternText	= /^[a-zA-Zа-яёА-ЯЁ\s]+$/iu,
	patternOtdel = /^[a-zA-Zа-яёА-ЯЁ\s]+$/iu,
	
	// массив с сообщениями об ошибке
	errorMess	= [
		'Введите реальное имя сотрудника', // [0]
		'Введите корректно-указанную должность', // [1]
		'Введите отдел сотрудника', // [2]
		'Введён несуществующий отдел', // [3]
		'Выберите дату рождения сотрудника', // [4]
		'Выберите дату начала работы сотрудника', // [5]
		'Введите должность сотрудника', // [6]
		'Загрузите аватар сотрудника' // [7]
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
			'newSotrud_name': function() 
			{
				if (formVal.newSotrud_name.length == 0 || patternText.test(formVal.newSotrud_name) == false) 
				{
					error = errorMess[0];
				}
			},
			'newSotrud_dolzh': function() 
			{
				if (formVal.newSotrud_dolzh.length == 0) 
				{
					error = errorMess[6];
				} else if (patternText.test(formVal.newSotrud_dolzh) == false) 
				{
					error = errorMess[1];
				}
			},
			'newSotrud_otdel': function() 
			{
				if (formVal.newSotrud_otdel.length == 0) 
				{
					error = errorMess[2];
				}
			},
			'newSotrud_rabdate': function() 
			{
				if (formVal.newSotrud_rabdate.length == 0) 
				{
					error = errorMess[5];
				}
			},
			'newSotrud_birthdate': function() 
			{
				if (formVal.newSotrud_birthdate.length == 0) 
				{
					error = errorMess[4];
				}
			},
			'newSotrud_file': function() 
			{
				if (formVal.newSotrud_file.length == 0) 
				{
					error = errorMess[7];
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
