new WOW().init()
const $every=(target,ev,fn)=>{document.querySelectorAll(target).forEach(el=>{if(!ev)return fn(el)
el.addEventListener(ev,e=>fn(el,e))})}
const number_format=number=>{number=number.replace(/\,/g,'')
return new Intl.NumberFormat('en-US').format(number)}
window.addEventListener('DOMContentLoaded',function(){let selects=document.querySelectorAll('select[value]')
selects.forEach(select=>{let value=select.getAttribute('value')
select.value=value})
document.querySelectorAll('aside [data-opened]').forEach(element=>{let is_opened=Number(element.getAttribute('data-opened'))
let list=element.querySelector('ul')
if(!is_opened){list.classList.add('hidden')}
element.querySelector('a:first-child').addEventListener('click',e=>{e.preventDefault()
list.classList.toggle('hidden')})})
let navbtn=document.querySelector('#nav-toggle')
let body=document.body
let main_content=document.querySelector('#dashboard-main-content')
let sidebar=document.querySelector('#sidebar')
if(navbtn){navbtn.addEventListener('click',e=>{e.preventDefault()
sidebar.classList.remove('-left-0')
body.setAttribute('data-nav-opened',1)
body.classList.add('overflow-hidden')
main_content.classList.add('opacity-50','overflow-hidden')})
main_content.addEventListener('click',e=>{if(body.getAttribute('data-nav-opened')){e.preventDefault()
sidebar.classList.add('-left-0')
body.removeAttribute('data-nav-opened')
body.classList.remove('overflow-hidden')
main_content.classList.remove('opacity-50')}})}
document.querySelectorAll('[data-faq-item]').forEach(item=>{item.addEventListener('click',e=>{e.preventDefault()
let el=item.parentElement.querySelector('p')
document.querySelectorAll('[data-faq-item]').forEach(faq=>{let faq_item=faq.parentElement.querySelector('p')
if(faq_item==el)return
faq_item.classList.add('hidden')})
el.classList.toggle('hidden')})})
document.querySelectorAll('a[disabled]').forEach(a=>{a.classList.add('cursor-not-allowed')
a.addEventListener('click',e=>e.preventDefault())})
document.querySelectorAll('[data-count]').forEach(el=>{if(el.getAttribute('data-counted')){return}
let pos=document.querySelector('#stats').offsetTop
let elpos=(el.offsetTop-window.innerHeight)
if(pos>elpos){el.setAttribute('data-counted',1)
let counter=new CountUp(el,el.getAttribute('data-count'),{startVal:0,decimalPlaces:0,duration:5})
counter.start()}})})