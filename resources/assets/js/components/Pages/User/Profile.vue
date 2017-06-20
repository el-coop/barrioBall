<script>
    export default{
        data(){
            return {
                userMatchFields: [
                    {
                        name: 'name',
                        title: 'Name'
                    },
                    {
                        name: 'pivot.role',
                        title: 'Role'
                    },
                    {
                        name: 'id',
                        title: 'View Match',
                        callback:this.link,
                    },
                ]
            }
        },

        methods: {
            link (value) {
                console.log(this);
                return "<a href='/matches/" + value + "'><i class='fa fa-search' ></i></a>";
            }
        }

    }
</script>
