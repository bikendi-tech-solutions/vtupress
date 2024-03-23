document.addEventListener('DOMContentLoaded',function(){(function(){const form=document.querySelector('#form-sell')
const network=form.querySelector('#network_id')
const dataplans=form.querySelector('#dataplans')
const phone=form.querySelector('#sell-phone')
const loader=form.querySelector('#loader')
const formbtn=form.querySelector('#form-button')
let plans={}
let selected_plans=null
const busy={busy(){loader.classList.remove('hidden')
formbtn.classList.add('hidden')},unbusy(){loader.classList.add('hidden')
formbtn.classList.remove('hidden')}}
network.addEventListener('change',function(){if(!this.value){return dataplans.setAttribute('disabled',true)}
selected_plans=plans[this.value]
dataplans.removeAttribute('disabled')
buildPlansList()});form.addEventListener('submit',e=>{e.preventDefault()
sendData()})
const fetchPlans=function(){busy.busy()
let uri=`${BASE_URL}/dashboard/networks/dataplans`
axios.get(uri).then(({data})=>{plans=data.plans}).finally(()=>busy.unbusy())}
const buildPlansList=function(){dataplans.innerHTML=''
let base_option=document.createElement('option')
base_option.value=''
base_option.innerText='- Select Data Plan -'
dataplans.appendChild(base_option)
selected_plans.map(plan=>{let option=document.createElement('option')
option.value=plan.id
option.innerText=plan.sale_price?`${plan.name} - N${Intl.NumberFormat('en-US').format(plan.sale_price)}`:plan.name
if(!plan.enabled){option.setAttribute('disabled',true)}
dataplans.appendChild(option)})}
function checkSendStatus(ref,count=0){busy.busy()
let check_uri=`${form.dataset.confirmUrl}/${ref}?count=${count}`
axios.get(check_uri).then(({data})=>{if(data.status=='processing'){return setTimeout(()=>checkSendStatus(ref,count+1),data.retry_after)}
busy.unbusy()
let icons={'awaiting_confirmation':'warning',true:'success',false:'error'}
let text=(data.status!=='awaiting_confirmation')?data.transaction.response:'Request timed out. Please confirm if transaction was sent successfully'
return Swal.fire({icon:icons[data.status],text,onClose:()=>{location.reload()}})})}
function sendData(){let send_uri=form.dataset.action
busy.busy()
let payload={network_id:network.value,phone:phone.value,plan_id:dataplans.value,operation_token:form.dataset.operationToken}
axios.post(send_uri,payload).then(({data})=>{if(data.transaction&&data.transaction.status.toLowerCase()==='processing'){return setTimeout(()=>checkSendStatus(data.transaction.ref),data.retry_after)}
if(data.status==='processing'){return setTimeout(()=>checkSendStatus(data.reference),data.retry_after)}
busy.unbusy()
if(data.status===true){return Swal.fire({icon:'success',text:data.transaction.response,onClose:()=>{location.reload()}})}
return Swal.fire({icon:'error',text:data.msg,onClose:()=>{location.reload()}})}).catch(()=>{busy.unbusy()
Swal.fire({icon:'warning',text:'Request timed out. Please confirm if transaction was sent successfully',onClose:()=>{location.reload()}})})}
fetchPlans()}())});