
document.addEventListener('DOMContentLoaded', async () => {
    if(document.getElementById('calendar')){
        // envoie la requete et attente de la réponse grâce à await
        const response = await fetch('/gite/reservation/dates')
        const result = await response.json()
        console.log(result)
        const options = {
            settings: {
                range: {
                    disabled: [result],
                    min: '2023-01-01',
                    max: '2025-01-01'
                },
                lang: 'fr-FR',
            },
            type: 'default',
        }
        const calendar = new VanillaCalendar('#calendar', options)
        calendar.init()
        //cache le loader et affiche le calendrier
        document.getElementById('loader').style.display = 'none'
        document.getElementById('calendar').style.display = 'block'
    }
})

