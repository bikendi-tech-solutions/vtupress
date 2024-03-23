const $http=axios.create({baseURL:`${BASE_URL}/api/v1`})
const $format=amt=>{return new Intl.NumberFormat('en-US',{minimumFractionDigits:2,maximumFractionDigits:2}).format(amt)}