var form = document.getElementById('newOtdelWithVal');

// коллекция полей формы из которой мы будем извлекать данные
var	elements = form.querySelectorAll('.form-control'),
	
	// объект кнопки, на который повесим обработчик события начала валидации формы
	btn	= document.getElementById('do_newOtdel'),
	
	// регулярные выражения
    patternText	= /^[a-zA-Zа-яёА-ЯЁ\s]+$/iu,
    patternRezhRabAddress = /^[a-zA-Zа-яёА-ЯЁ0-9:.\-,\s]+$/iu,
    patternPhone = /^(\+7|8|7)\([0-9]{3}\)-[0-9]{3}-[0-9]{2}-[0-9]{2}$/,
	
	// массив с сообщениями об ошибке
	errorMess	= [
		'Введите реальное название отдела', // [0]
		'Введите название отдела', // [1]
		'Введите режим работы', // [2]
		'Неккоректно введён режим работы', // [3]
		'Выберите дату образования отделения', // [4]
		'Введите корректный адрес', // [5]
		'Введите корректный номер телефона' // [6]
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
			'newOtdel_name': function() 
			{
				if (formVal.newOtdel_name.length == 0) 
				{
					error = errorMess[1];
				} else if (patternText.test(formVal.newOtdel_name) == false) 
				{
					error = errorMess[0];
				}
			},
			'newRezhim_raboty': function() 
			{
				if (formVal.newRezhim_raboty.length == 0) 
				{
					error = errorMess[2];
				} else if (patternRezhRabAddress.test(formVal.newRezhim_raboty) == false) 
				{
					error = errorMess[3];
				}
			},
			'newDate_obr': function() 
			{
				if (formVal.newDate_obr.length == 0) 
				{
					error = errorMess[4];
				}
            },
            'address': function() 
			{
				if (formVal.address.length == 0 || patternRezhRabAddress.test(formVal.address) == false) 
				{
					error = errorMess[5];
				}
			},
			'phone': function() 
			{
				if (patternPhone.test(formVal.phone) == false) 
				{
					error = errorMess[6];
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